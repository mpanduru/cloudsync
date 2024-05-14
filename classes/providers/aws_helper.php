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
require($CFG->dirroot . '/local/cloudsync/constants.php');

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
                'DeviceName' => '/dev/xvda',
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
                            'Value' => 'cloudsync_' . $name . '_' . SITE_TAG,
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
     * @param string $key_name the name of the key
     * @param string $owner the name of the owner of the key
     * @return object an object that holds the created key name and its value
     */
    public function create_key(Aws\Ec2\Ec2Client $ec2Client, $key_name, $owner) {
        $result = $ec2Client->createKeyPair([
            'KeyFormat' => 'pem',
            'KeyName' => 'cloudsync_' . $key_name . '_' . SITE_TAG,
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
                'cloudsync_' . $key_name . '_' . SITE_TAG,
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
            'GroupName' => 'cloudsync_' . $name . '_' . SITE_TAG,
            'TagSpecifications' => [
                [
                    'ResourceType' => 'security-group',
                    'Tags' => [
                        [
                            'Key' => 'Purpose',
                            'Value' => 'cloudsync_' . $tag . '_' . SITE_TAG,
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
        $result = $ec2Client->terminateInstances([
            'InstanceIds' => [
                $instance_id,
            ],
        ]);

        return true;
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
    public function delete_security_group(Aws\Ec2\Ec2Client $ec2Client, $group_id) {
        $result = $ec2Client->deleteSecurityGroup([
            'GroupId' => $group_id,
        ]);

        return true;
    }

     /**
     * 
     * Test an AWS account to make sure a subscription is valid
     *
     * @param string $region the region of the connection
     * @param string $access_key the access_key of the aws subscription
     * @param string $access_key_secret the access_key_secret of the aws subscription
     * @return bool whether or not the subscription is valid
     */
    public function test_secrets($region, $access_key, $access_key_secret) {
        $client = $this->create_connection($region, $access_key, $access_key_secret);
        $test_sg_ssh = $this->create_security_group_with_rule($client, 'None', 'test_SG', 'ssh',
                                                              22, 'tcp', '0.0.0.0/0', 'None');
        $test_ssh_key = $this->create_key($client, 'test_keypair', 'test_owner');   
        $public_ssh_key = $this->get_public_key($client, 'test_keypair');                                                           
        $test_instance = $this->create_instance($client, 'test_owner', 'test_instance', 'ami-04e5276ebb8451442', 't2.micro',
                                                8, 'None', $test_ssh_key->key_name, '', $public_ssh_key);

        $this->delete_instance($client, $test_instance);
        $this->delete_key($client, $test_ssh_key->key_name);       
        $this->delete_security_group($client, $test_sg_ssh);
        
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
                        'cloudsync_' . $tag . '_' . SITE_TAG,
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
                        'cloudsync_' . $tag . '_' . SITE_TAG,
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
        $string_data = "#cloud-config\ncloud_final_modules:\n- [users-groups,always]\nusers:\n  - name: " . $username . "\n    groups: [ wheel ]\n    sudo: [ \"ALL=(ALL) NOPASSWD:ALL\" ]\n    shell: /bin/bash\n    ssh-authorized-keys:\n    - " . $public_key;

        $user_data = base64_encode($string_data);
        return $user_data;
    }
}