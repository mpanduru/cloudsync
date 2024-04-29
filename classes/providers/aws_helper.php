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

class aws_helper {
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

    public function describe_instances(Aws\Ec2\Ec2Client $ec2Client) {
        $result = $ec2Client->describeInstances();

        foreach ($result['Reservations'] as $reservation) {
            foreach ($reservation['Instances'] as $instance) {
                echo "InstanceId: {$instance['InstanceId']} - {$instance['State']['Name']} \n";
            }
        }
    }

    public function describe_instance(Aws\Ec2\Ec2Client $ec2Client, $instance_id) {
        $result = $ec2Client->describeInstances([
            'InstanceIds' => [$instance_id,]
        ]);

        var_dump($result);
    }

    public function create_instance(Aws\Ec2\Ec2Client $ec2Client, $owner, $name, $image_id, $instance_type, $rootdisk, $seconddisk, $key_name) {
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
        
        $result = $ec2Client->runInstances([
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
        ]);

        $Instance = $result->get('Instances');
        return $Instance[0]['InstanceId'];
    }

    public function create_key(Aws\Ec2\Ec2Client $ec2Client, $key_name, $owner) {
        $result = $ec2Client->createKeyPair([
            'KeyFormat' => 'pem',
            'KeyName' => $key_name,
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

    public function wait_instance(Aws\Ec2\Ec2Client $ec2Client, $instance_id) {
        $waiterName = 'InstanceRunning';
        $waiterOptions = ['InstanceIds' => [
            $instance_id,
            ]
        ];

        try {
            $ec2Client->waitUntil($waiterName, $waiterOptions);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}