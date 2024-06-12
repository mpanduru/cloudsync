<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Plugin version and other meta-data are defined here.
 *
 * @package     local_cloudsync
 * @copyright   2024 Constantin-Marius Panduru <constantin.panduru@student.upt.ro>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $CFG;
require($CFG->dirroot . '/local/cloudsync/vendor/autoload.php');

// Class that will be used to administrate the resources that live
// on AWS subscriptions
class aws_helper {

     /**
     * Create a connection to the AWS cloud
     *
     * @param string $region the region of the connection
     * @param string $access_key the access_key of the aws subscription
     * @param string $access_key_secret the access_key_secret of the aws subscription
     * @return Aws\Ec2\Ec2Client the client that will be used to interact with AWS
     */
    public function create_connection($region, $access_key, $access_key_secret) {
        $ec2Client = new Aws\Ec2\Ec2Client([
            'region' => $region,
            'version' => '2016-11-15',
            'credentials' => [
                'key' => $access_key,
                'secret' => $access_key_secret
            ]
        ]);

        return $ec2Client;
    }

     /**
     * Create a virtual machine for a moodle user via an existing virtual machine from db
     */
    public function cloudsync_create_virtualmachine($vm, $secrets, $subscriptionmanager, $keypair, $keypairmanager, $owner_name) {
        $keyname = $owner_name  . '_' . $vm->name . '_key';
        $keypair_id = CLOUDSYNC_RESOURCE . $keyname . '_' . $vm->region . '_'. SITE_TAG;

        // Create the connection to the cloud
        $client = $this->create_connection($vm->region, $secrets->access_key_id, $secrets->access_key_secret);

        // Create the ssh key
        if($keypair){
            if(!$this->exists_key($client, $keypair->keypair_id)) {
                throw new Exception("Key exists in db but not in cloud");
            }
        } else {
            if($this->exists_key($client, $keypair_id)) {
                throw new Exception("Key exists in cloud but not in db");
            } else {
                $keypair = new keypair($vm->owner_id, $vm->subscription_id, $keyname, $vm->region);
                $key = $this->create_key($client, $keypair_id, $owner_name);
                $key_public = $this->get_public_key($client, $keypair_id);
                $keypair->setKeypairPublicValue($key_public);
                $keypair->setKeypairValue($key->private_key_value);
                $keypair->setKeypairId($key->key_name);
                $id = $keypairmanager->create_key($keypair);
                $keypair->setId($id);
            }
        }

        $vm->setKeypair($keypair->id);

        $sg_id = $this->get_security_group($client, CLOUDSYNC_RESOURCE . SSH_TAG);
        $sg_id = $sg_id['SecurityGroups'][0]['GroupId'];

        // Create the instance
        $instance_id = $this->create_instance($client, $owner_name, CLOUDSYNC_RESOURCE . $vm->name . '_' . SITE_TAG, 
                                AWS_FIELDS["os_image"][$vm->region][$vm->os], $vm->type, $vm->rootdisk_storage, $vm->seconddisk_storage, 
                                $keypair->keypair_id, $sg_id, $keypair->public_value);

        return $instance_id;
    }

     /**
     * Describe all the instances that live on an AWS subscription
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     */
    public function describe_instances(Aws\Ec2\Ec2Client $ec2Client) {
        $result = $ec2Client->describeInstances();

        foreach ($result['Reservations'] as $reservation) {
            foreach ($reservation['Instances'] as $instance) {
                echo "InstanceId: {$instance['InstanceId']} - {$instance['State']['Name']} \n";
            }
        }
    }

     /**
     * Get info about a specified instance
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $instance_id the id of the searched instance (from the aws cloud)
     * @return Aws\Result $result the instances that match the search
     */
    public function describe_instance(Aws\Ec2\Ec2Client $ec2Client, $instance_id) {
        $result = $ec2Client->describeInstances([
            'InstanceIds' => [$instance_id,]
        ]);

        return $result;
    }

     /**
     * Create an instance on the AWS cloud
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $owner the name of the owner of the virtual machine
     * @param string $name the name of the virtual machine
     * @param string $image_id the id of the os image used for the virtual machine
     * @param string $instance_type the type of the virtual machine
     * @param int $rootdisk the storage for the root disk of the virtual machine
     * @param int|string $seconddisk the storage for the secondary disk of the virtual machine
     * @param string $key_name the name of the ssh key used to connect to the virtual machine
     * @param string $security_group_id the name of the security group used to create the virtual machine
     * @return string id of the created instance in AWS
     */
    public function create_instance(Aws\Ec2\Ec2Client $ec2Client, $owner, $name, $image_id, $instance_type, $rootdisk, $seconddisk, $key_name, $security_group_id, $public_key) {
        $blockDeviceMappings = [
            [
                'DeviceName' => '/dev/sda1',
                'Ebs' => [
                    'DeleteOnTermination' => true,
                    'VolumeSize' => $rootdisk,
                    'VolumeType' => 'gp3',
                ],
            ]
        ];
        if ($seconddisk != 0 && $seconddisk != 'None') {
            $blockDeviceMappings[] = [
                'DeviceName' => '/dev/sdb',
                'Ebs' => [
                    'DeleteOnTermination' => true,
                    'VolumeSize' => $seconddisk,
                    'VolumeType' => 'gp3',
                ],
            ];
        }

        $user_data = $this->create_user_data($owner, $public_key);
        
        $params = [
            'BlockDeviceMappings' => $blockDeviceMappings,
            'ImageId' => $image_id,
            'InstanceType' => $instance_type,
            'KeyName' => $key_name,
            'MaxCount' => 1,
            'MinCount' => 1,
            'TagSpecifications' => [
                [
                    'ResourceType' => 'instance',
                    'Tags' => [
                        [
                            'Key' => 'Owner',
                            'Value' => $owner,
                        ],
                        [
                            'Key' => 'Name',
                            'Value' => $name,
                        ],
                    ],
                ],
            ],
            'UserData' => $user_data,
        ];

        if (!empty($security_group_id)) {
            $params['SecurityGroupIds'] = [$security_group_id];
        }

        $result = $ec2Client->runInstances($params);

        $Instance = $result->get('Instances');
        return $Instance[0]['InstanceId'];
    }

     /**
     * Create an ssh key on the AWS cloud
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $keypair_id the name of the key
     * @param string $owner the name of the owner of the key
     * @return object an object that holds the created key name and its value
     */
    public function create_key(Aws\Ec2\Ec2Client $ec2Client, $keypair_id, $owner) {
        $result = $ec2Client->createKeyPair([
            'KeyFormat' => 'pem',
            'KeyName' => $keypair_id,
            'KeyType' => 'rsa',
            'TagSpecifications' => [
                [
                    'ResourceType' => 'key-pair',
                    'Tags' => [
                        [
                            'Key' => 'Owner',
                            'Value' => $owner,
                        ],
                    ],
                ],
            ],
        ]);

        $key = (object)[
            'key_name' => $result->get('KeyName'),
            'private_key_value' => $result->get('KeyMaterial')
        ];

        return $key;
    }

     /**
     * Get the public value of a keypair in AWS
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $key_name the name of the key
     * @return string the public key
     */
    public function get_public_key(Aws\Ec2\Ec2Client $ec2Client, $key_name) {
        $result = $ec2Client->describeKeyPairs([
            'IncludePublicKey' => true,
            'KeyNames' => [
                $key_name,
            ],
        ]);

        return $result['KeyPairs'][0]['PublicKey'];
    }

     /**
     * Verify an existing key on the AWS Cloud
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $key_name the name of the key
     * @return boolean whether or not the key exists on the cloud
     */
    public function exists_key(Aws\Ec2\Ec2Client $ec2Client, $key_name) {
        $waiterName = 'KeyPairExists';
        $waiterOptions = [
            'KeyNames' => [$key_name,],
            '@waiter' => [
                'delay' => 3,
                'maxAttempts' => 1
            ]
        ];

        try {
            $ec2Client->waitUntil($waiterName, $waiterOptions);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

     /**
     * 
     * Wait for an instance to start
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $instance_id the id of the instance
     * @return bool whether or not the instance is running
     */
    public function wait_instance(Aws\Ec2\Ec2Client $ec2Client, $instance_id) {
        $waiterName = 'InstanceRunning';
        $waiterOptions = [
            'InstanceIds' => [$instance_id,]
        ];

        try {
            $ec2Client->waitUntil($waiterName, $waiterOptions);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

     /**
     * 
     * Create a security group on the AWS Cloud and add an inbound rule to it
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $sg_description short description for the security group
     * @param string $name the name of the security group
     * @param string $tag a tag for the security group, can be a purpose
     * @param int $port the open port for the security group rule
     * @param string $protocol the protocol used for the rule
     * @param string $range the ip range used for the rule
     * @param string $rule_description short description for the rule
     * @return string the security group ID
     */
    public function create_security_group_with_rule(Aws\Ec2\Ec2Client $ec2Client, $sg_description, $name, $tag, $port, $protocol, $range, $rule_description) {
        $sg_result = $ec2Client->createSecurityGroup([
            'Description' => $sg_description,
            'GroupName' => $name,
            'TagSpecifications' => [
                [
                    'ResourceType' => 'security-group',
                    'Tags' => [
                        [
                            'Key' => 'Purpose',
                            'Value' => $tag,
                        ],
                    ],
                ],
            ],
        ]);

        $rule_result = $ec2Client->authorizeSecurityGroupIngress([
            'GroupId' => $sg_result['GroupId'],
            'IpPermissions' => [
                [
                    'FromPort' => $port,
                    'IpProtocol' => $protocol,
                    'IpRanges' => [
                        [
                            'CidrIp' => $range,
                            'Description' => $rule_description,
                        ],
                    ],
                    'ToPort' => $port,
                ],
            ],
        ]);

        return $sg_result['GroupId'];
    }

     /**
     * 
     * Delete an instance
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $instance_id the cloud instance id
     * @return bool whether or not the instance delete action started
     */
    public function delete_instance(Aws\Ec2\Ec2Client $ec2Client, $instance_id) {
        try {
            $result = $ec2Client->terminateInstances([
                'InstanceIds' => [
                    $instance_id,
                ],
            ]);
            return true;
        } catch(Exception $e){
            return false;
        }
    }

    public function cloudsync_delete_instance($vm, $secrets) {
        $client = $this->create_connection($vm->region, $secrets->access_key_id, $secrets->access_key_secret);

        return $this->delete_instance($client, $vm->instance_id);
    }

     /**
     * 
     * Delete a key
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $name the name of the key
     * @return bool whether or not the keypair delete action started
     */
    public function delete_key(Aws\Ec2\Ec2Client $ec2Client, $name) {
        $result = $ec2Client->deleteKeyPair([
            'KeyName' => $name,
        ]);

        return true;
    }

     /**
     * 
     * Delete a security group
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $group_id the security group id
     * @return bool whether or not the security group delete action started
     */
    public function delete_security_group_by_id(Aws\Ec2\Ec2Client $ec2Client, $group_id) {
        $result = $ec2Client->deleteSecurityGroup([
            'GroupId' => $group_id,
        ]);

        return true;
    }

     /**
     * Get an existing security group on the AWS Cloud
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $tag the tag of the security group
     * @return Aws\Result the searched security group
     */
    public function get_security_group(Aws\Ec2\Ec2Client $ec2Client, $tag) {
        $result = $ec2Client->describeSecurityGroups([
            'Filters' => [
                [
                    'Name' => 'tag:Purpose',
                    'Values' => [
                        $tag,
                    ],
                ],
            ],
        ]);

        return $result;
    }

     /**
     * Verify an existing security group on the AWS Cloud
     *
     * @param Aws\Ec2\Ec2Client $ec2Client the client of the connection (created with create_connection function)
     * @param string $tag the tag of the security group
     * @return boolean whether or not the key exists on the cloud
     */
    public function exists_security_group(Aws\Ec2\Ec2Client $ec2Client, $tag) {
        $waiterName = 'SecurityGroupExists';
        $waiterOptions = [
            'Filters' => [
                [
                    'Name' => 'tag:Purpose',
                    'Values' => [
                        $tag,
                    ],
                ],
            ],
            '@waiter' => [
                'delay' => 3,
                'maxAttempts' => 1
            ]
        ];

        try {
            $ec2Client->waitUntil($waiterName, $waiterOptions);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

     /**
     * 
     * Create Userdata to use for the vm
     *
     * @param string $username the username of the vm
     * @param string $public_key the public key of the vm
     * @return string the userdata
     */
    public function create_user_data($username, $public_key) {
        $string_data = "#cloud-config
        cloud_final_modules:
          - [users-groups,always]
        preserve_hostname: false
        hostname: " . SITE_TAG . "
        fqdn: " . SITE_TAG . ".local
        manage_etc_hosts: true
        users:
          - name: " . $username . "
            groups: [ wheel ]
            sudo: [ \"ALL=(ALL) NOPASSWD:ALL\" ]
            shell: /bin/bash
            ssh-authorized-keys:
              - " . $public_key;

        $user_data = base64_encode($string_data);
        return $user_data;
    }

    public function get_instance_connection_string($vm, $secrets) {
        $client = $this->create_connection($vm->region, $secrets->access_key_id, $secrets->access_key_secret);

        $instance_details = $this->describe_instance($client, $vm->instance_id);
        
        return $instance_details['Reservations'][0]['Instances'][0]['PublicDnsName'];
    }

    public function get_instance_status($vm, $secrets) {
        $client = $this->create_connection($vm->region, $secrets->access_key_id, $secrets->access_key_secret);

        $instance_details = $this->describe_instance($client, $vm->instance_id);
        
        return $instance_details['Reservations'][0]['Instances'][0]['State']['Name'];
    }

    public function get_netinfo($vm, $secrets) {
        $client = $this->create_connection($vm->region, $secrets->access_key_id, $secrets->access_key_secret);

        $instance_details = $this->describe_instance($client, $vm->instance_id);
        
        $netinfo = (object)[ 
            'private_ip' => $instance_details['Reservations'][0]['Instances'][0]['PrivateIpAddress'],
            'public_ip' => $instance_details['Reservations'][0]['Instances'][0]['PublicIpAddress'],
            'public_dns' => $instance_details['Reservations'][0]['Instances'][0]['PublicDnsName'],
        ];

        return $netinfo;
    }

    /**
    * 
    * Test an AWS account to make sure a subscription is valid
    *
    * @param object $secrets the secrets that are going to be tested
    * @return bool whether or not the subscription is valid
    */
   public function create_initial_resources($secrets) {
    foreach (SUPPORTED_AWS_REGIONS as $region) {
        $client = $this->create_connection($region, $secrets->access_key_id, $secrets->access_key_secret);
        $sg_ssh = $this->create_security_group_with_rule($client, SSH_DESCRIPTION, CLOUDSYNC_RESOURCE . SSH_SECURITY_GROUP . $region . '_' . SITE_TAG, 
                                                         CLOUDSYNC_RESOURCE . SSH_TAG,  SSH_PORT, 'tcp', '0.0.0.0/0', SSH_RULE);
                                                    
        if(!$sg_ssh) {
            return false;
        }
    }
    
    return true;
    }

    public function delete_security_group($client, $name) {
        $result = $client->deleteSecurityGroup([
            'GroupName' => $name,
        ]);

        if($result)
            return true;
        return false;
    }

    public function wait_instance_terminated(Aws\Ec2\Ec2Client $ec2Client, $instance_id) {
        $waiterName = 'InstanceTerminated';
        $waiterOptions = [
            'InstanceIds' => [$instance_id,]
        ];

        try {
            $ec2Client->waitUntil($waiterName, $waiterOptions);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function destroy_resources($secrets, $active_vms, $keys, $subscription) {
        foreach ($active_vms as $vm) {
            $client = $this->create_connection($vm->region, $secrets->access_key_id, $secrets->access_key_secret);
            $this->delete_instance($client, $vm->instance_id);
            $vm->status = 'Deleted';
        }

        foreach($active_vms as $vm) {
            $client = $this->create_connection($vm->region, $secrets->access_key_id, $secrets->access_key_secret);

            $this->wait_instance_terminated($client, $vm->instance_id);
        }
        foreach (SUPPORTED_AWS_REGIONS as $region) {
            $client = $this->create_connection($region, $secrets->access_key_id, $secrets->access_key_secret);

            if (!$this->delete_security_group($client, CLOUDSYNC_RESOURCE . SSH_SECURITY_GROUP . $region . '_' . SITE_TAG))
                return false;
        }
        foreach ($keys as $key) {
            $client = $this->create_connection($key->region, $secrets->access_key_id, $secrets->access_key_secret);

            $this->delete_key($client, $key->keypair_id);
        }

        return true;
    }
}