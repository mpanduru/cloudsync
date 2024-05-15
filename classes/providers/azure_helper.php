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
require($CFG->dirroot . '/local/cloudsync/classes/managers/subscriptionmanager.php');
require($CFG->dirroot . '/local/cloudsync/constants.php');

// Class that will be used to administrate the resources that live
// on AZURE subscriptions
class azure_helper {

     /**
     * Build a request that will be used to manage azure resources
     *
     * @param string $url the url of the request
     * @param string $method the method (GET, PUT, POST, DELETE)
     * @param array|null $header the header of the request
     * @param string|null $body the body of the request
     * @param array|null $parameters the parameters of the request
     * @return HTTP_Request2 the build request that can be sent
     */
    public function build_request($url, $method, $header, $body, $post_parameters){
        $request = new HTTP_Request2();

        $request->setUrl($url);
        $request->setMethod($method);
        $request->setConfig(array(
        'follow_redirects' => TRUE
        ));
        if($header){
            $request->setHeader($header);
        }
        if($body) {
            $request->setBody($body);
        }
        if($post_parameters){
            $request->addPostParameter($post_parameters);
        }

        return $request;
    }

     /**
     * Send a request
     *
     * @param HTTP_Request2 $request the request to be sent
     * @param mixed $wrong_status a value to be returned in case of wrong status
     * @param mixed $exception a value to be returned in case of an Exception
     * @param boolean $jsondecode whether or not to decode the result
     * @return object|mixed the response body or a value in case of a failure
     */
    public function send_request($request, $wrong_status, $exception, $jsondecode){
        try {
            $response = $request->send();
            if ($response->getStatus() == 200 || $response->getStatus() == 201) {
                if($jsondecode) {
                    $result = json_decode($response->getBody());
                    if (json_last_error() === JSON_ERROR_NONE) {
                        return $result;
                    } else {
                        throw new Exception('Error decoding JSON');
                    }
                }
                return $response->getBody();
            }
            else {
                return $response->getBody();
            }
          }
          catch(HTTP_Request2_Exception $e) {
            return $exception;
        }
    }

     /**
     * Get an AZURE token
     *
     * @param string $tenant_id the tenant ID of the subscription
     * @param string $client_id the client ID of the subscription
     * @param string $client_secret the secret of the specified client
     * @return object|false returns the bearer token if everything goes well, otherwise false
     */
    public function get_token($tenant_id, $client_id, $client_secret){
        $request_url = 'https://login.microsoftonline.com/'.$tenant_id.'/oauth2/token';
        $request_post_parameters = array(
            'grant_type' => 'client_credentials',
            'client_id' => $client_id,
            'client_secret' => $client_secret,
            'resource' => 'https://management.azure.com/'
        );

        $request = $this->build_request($request_url, HTTP_Request2::METHOD_POST, null, null, $request_post_parameters);
        $token = $this->send_request($request, 'Wrong status', 'Exception', true);

        if($token != 'Wrong status' && $token != 'Exception')
            return $token;
        return false;
    }

     /**
     * Creates a connection to the Azure cloud
     *
     * @param subscriptionmanager $subscriptionmanager a subscription manager that will be used to retrieve an existing token
     * @param string $subscription_id the ID of the used subscription
     * @return object|false returns the bearer token if everything goes well, otherwise false
     */
    public function create_connection($subscriptionmanager, $subscription_id){
        $azure_secrets = $subscriptionmanager->get_secrets_by_subscription_id($subscription_id);
        if (empty($azure_secrets->token) || empty($azure_secrets->token_expires_on) || time() > $azure_secrets->token_expires_on) {
            $token = $this->get_token($azure_secrets->tenant_id, $azure_secrets->app_id, $azure_secrets->secret);
            if ($token) {
                $azure_secrets->token = $token->access_token;
                $azure_secrets->token_expires_on = $token->expires_on;
                $subscriptionmanager->update_subscription_secrets($subscription_id, $azure_secrets);
                return $token;
            }
            return $token;
        } else {
            return (object)[
                'access_token' => $azure_secrets->token,
                'expires_on' => $azure_secrets->token_expires_on,
            ];
        }
    }

     /**
     * Creates a network security group with a specific inbound rule to the Azure cloud
     *
     * @param object $token the token used to connect to the Azure cloud
     * @param string $azure_subscription_id the ID of the used subscription
     * @param string $resource_group the resource group where the security group will be created
     * @param string $location the region where the security group will be created
     * @param string $name the name of the security group
     * @param string $owner the owner used to tag the security group
     * @param int $port the port opened for the security group rule
     * @param string $protocol the protocol used for the security group rule
     * @param string $range the range for the security group rule
     * @param int $priority the priority of the security group rule
     * @param string $rule_name the name of the the rule for the security group
     * @return object|false returns the security group if everything goes well, otherwise false
     */
    public function create_security_group_with_rule($token, $azure_subscription_id, $resource_group, $location, 
                                                    $name, $owner, $port, $protocol, $range, $priority, $rule_name) {

        $url = 'https://management.azure.com/subscriptions/'.$azure_subscription_id.'/resourceGroups/'.$resource_group.
                '/providers/Microsoft.Network/networkSecurityGroups/'.$name.'?api-version=2023-09-01';
        
        $request_body = "
        {
            \"tags\": {
                \"owner\": \"".$owner."\"
            },
            \"properties\": {
                \"securityRules\": [
                    {
                        \"name\": \"".$rule_name."\",
                        \"properties\": {
                            \"protocol\": \"".$protocol."\",
                            \"sourceAddressPrefix\": \"*\",
                            \"destinationAddressPrefix\": \"".$range."\",
                            \"access\": \"Allow\",
                            \"destinationPortRange\": \"".$port."\",
                            \"sourcePortRange\": \"*\",
                            \"priority\": ".$priority.",
                            \"direction\": \"Inbound\"
                        }
                    }
                ]
            },
            \"location\": \"".$location."\"
        }";
        
        $header = array(
            'Authorization' => 'Bearer ' . $token->access_token,
            'Content-Type' => 'application/json'
        );
        
        $request = $this->build_request($url, HTTP_Request2::METHOD_PUT, $header, $request_body, null);

        $response = $this->send_request($request, false, false, true);

        return $response;
    }

     /**
     * Creates a virtual network and a subnet inside it on the Azure cloud
     *
     * @param object $token the token used to connect to the Azure cloud
     * @param string $azure_subscription_id the ID of the used subscription
     * @param string $resource_group the resource group where the virtual network will be created
     * @param string $location the region where the virtual network will be created
     * @param string $name the name of the virtual network
     * @param string $owner the owner used to tag the virtual network
     * @param string $net_range the range of the virtual network
     * @param string $subnet_range the range of the subnet inside the virtual network
     * @param string $subnet_name the name of the subnet inside the virtual network
     * @return object|false returns the virtual network if everything goes well, otherwise false
     */
    public function create_virtual_network($token, $azure_subscription_id, $resource_group, $location, $name, $owner, $net_range, $subnet_range,
                                            $subnet_name) {
        $url = 'https://management.azure.com/subscriptions/'.$azure_subscription_id.'/resourceGroups/'.$resource_group.
                '/providers/Microsoft.Network/virtualNetworks/'.$name.'?api-version=2023-09-01';

        $request_body = "
        {
            \"tags\": {
                \"owner\": \"".$owner."\"
            },
            \"properties\": {
                \"addressSpace\": {
                    \"addressPrefixes\": [
                    \"".$net_range."\"
                    ]
                },
                \"subnets\": [
                    {
                        \"name\": \"".$subnet_name."\",
                        \"properties\": {
                            \"addressPrefix\": \"".$subnet_range."\"
                        }
                    }
                ]
            },
            \"location\": \"".$location."\"
        }
        ";
        
        $header = array(
            'Authorization' => 'Bearer ' . $token->access_token,
            'Content-Type' => 'application/json'
        );

        $request = $this->build_request($url, HTTP_Request2::METHOD_PUT, $header, $request_body, null);

        $response = $this->send_request($request, false, false, true);

        return $response;
    }

     /**
     * Creates a virtual machine on the Azure cloud
     *
     * @param object $token the token used to connect to the Azure cloud
     * @param string $azure_subscription_id the ID of the used subscription
     * @param string $resource_group the resource group where the virtual machine will be created
     * @param string $location the region where the virtual machine will be created
     * @param string $name the name of the virtual machine
     * @param string $owner the owner used to tag the virtual network
     * @param string $type the type of the virtual machine (VM Size)
     * @param string $disk_name the name of the OS disk for the virtual machine
     * @param int $disk_size the size of the OS disk for the virtual machine
     * @param string $os_username the username inside the virtual machine OS
     * @param string $host_name the hostname inside the virtual machine OS
     * @param string $nic_name the name assigned for the nic of the virtual machine
     * @param string $ip_config_name a name assigned for the ip config of the virtual machine network
     * @param string $public_ip_name the name assigned for the public ip of the virtual machine
     * @param string $vnet_name the name of the virtual network used for the virtual machine
     * @param string $subnet_name the name of the subnet used for the virtual machine
     * @param string $securitygroup_name the name of the security group used for the virtual machine
     * @param string $public_key the public key for the virtual machine
     * @return object|false returns the virtual machine if everything goes well, otherwise false
     */
    public function create_instance($token, $azure_subscription_id, $resource_group, $location, $name, $owner, $type, $disk_name, $disk_size,
                                    $os_username, $host_name, $nic_name, $ip_config_name, $public_ip_name, $vnet_name, 
                                    $subnet_name, $securitygroup_name, $public_key) {
        $url = 'https://management.azure.com/subscriptions/'.$azure_subscription_id.'/resourceGroups/'.$resource_group.
                '/providers/Microsoft.Compute/virtualMachines/'.$name.'?api-version=2023-09-01';

        $request_body = "
        {
            \"location\": \"".$location."\",
            \"tags\": {
                \"owner\": \"".$owner."\"
            },
            \"properties\": {
                \"hardwareProfile\": {
                    \"vmSize\": \"".$type."\"
                },
                \"additionalCapabilities\": {
                    \"hibernationEnabled\": false
                },
                \"storageProfile\": {
                    \"imageReference\": {
                        \"publisher\": \"canonical\",
                        \"offer\": \"0001-com-ubuntu-server-jammy\",
                        \"sku\": \"22_04-lts-gen2\",
                        \"version\": \"latest\"
                    },
                    \"osDisk\": {
                        \"osType\": \"Linux\",
                        \"name\": \"".$disk_name."\",
                        \"createOption\": \"FromImage\",
                        \"caching\": \"ReadWrite\",
                        \"managedDisk\": {
                            \"storageAccountType\": \"Premium_LRS\"
                        },
                        \"deleteOption\": \"Delete\",
                        \"diskSizeGB\": ".$disk_size."
                    },
                    \"diskControllerType\": \"SCSI\"
                },
                \"osProfile\": {
                    \"computerName\": \"".$host_name."\",
                    \"adminUsername\": \"".$os_username."\",
                    \"linuxConfiguration\": {
                        \"disablePasswordAuthentication\": true,
                        \"ssh\": {
                            \"publicKeys\": [
                                {
                                    \"path\": \"/home/".$os_username."/.ssh/authorized_keys\",
                                    \"keyData\": \"ssh-rsa AAAAB3NzaC1yc2EAAAADAQABAAABgQCv6/7owCjWc0kDW+kwdYuW6OKHTg5PivdrOnNyidyna7YVVA6xot7LlitsuNq9QVU0aMXCmKxIZkYmpMXY6kc9TUqX/zgrfMp83nqokHrQ/oqFHhYP3Fh8fj9J3ndXziqYEFJaVQoP3bltREWuyMfXj8qu/pyq1qDSdO77qjwXl3TTzCuKqjEA85F5sq52/J9Y9nRlccK872zdygO2XlqFE63uN7l75Q0MBUgJnw+nfLan6nLUywApERlAttxrSC5Z/lykqKXVNPzUeBCdpXN2Bt/MfFJhaGypku2lHVYb6IQxFcoNivrbd2eQ8OBKD7b3NjBf6bkO2+UtkVEJEpWDlBmNG/yg+LrDjcuiBLYQ2U6MoWPzyHVoqq4kSVNOOUz94+oQ6Iesuxm/0gETLAJc4fNEkELzk9vy4kZkozNW/nSJdTluouyGxE92eJcq1SqMy7em2tSlHsbMtI0ylj40lMPDzYQltdOwtyQgEUwoAb86fim6ramwsdypL8g1M18= marius@MariusP\"
                                }
                            ]
                        }
                    }
                },
                \"securityProfile\": {
                    \"uefiSettings\": {
                        \"secureBootEnabled\": true,
                        \"vTpmEnabled\": true
                    },
                    \"securityType\": \"TrustedLaunch\"
                },
                \"networkProfile\": {
                    \"networkApiVersion\": \"2020-11-01\",
                    \"networkInterfaceConfigurations\": [
                        {
                            \"name\": \"".$nic_name."\",
                            \"properties\": {
                                \"primary\": true,
                                \"deleteOption\": \"Delete\",
                                \"ipConfigurations\": [
                                    {
                                        \"name\": \"".$ip_config_name."\",
                                        \"properties\": {
                                            \"primary\": true,
                                            \"publicIPAddressConfiguration\": {
                                                \"name\": \"".$public_ip_name."\",
                                                \"properties\": {
                                                    \"deleteOption\": \"Delete\",
                                                    \"publicIPAllocationMethod\": \"Static\"
                                                }
                                            },
                                            \"subnet\": {
                                                \"id\": \"/subscriptions/".$azure_subscription_id."/resourceGroups/".$resource_group.
                                                "/providers/Microsoft.Network/virtualNetworks/".$vnet_name."/subnets/".$subnet_name."\"
                                            },
                                            \"privateIPAddressVersion\": \"IPv4\"
                                        }
                                    }
                                ],
                                \"networkSecurityGroup\": {
                                    \"id\": \"/subscriptions/".$azure_subscription_id."/resourceGroups/".$resource_group.
                                    "/providers/Microsoft.Network/networkSecurityGroups/".$securitygroup_name."\"
                                }
                            }
                        }
                    ]
                },
                \"diagnosticsProfile\": {
                    \"bootDiagnostics\": {
                        \"enabled\": true
                    }
                }
            }
        }";

        $header = array(
            'Authorization' => 'Bearer ' . $token->access_token,
            'Content-Type' => 'application/json'
        );

        $request = $this->build_request($url, HTTP_Request2::METHOD_PUT, $header, $request_body, null);

        $response = $this->send_request($request, false, false, true);

        return $response;
    }

     /**
     * Creates a keypair on the Azure cloud
     *
     * @param object $token the token used to connect to the Azure cloud
     * @param string $azure_subscription_id the ID of the used subscription
     * @param string $resource_group the resource group where the keypair will be created
     * @param string $location the region where the keypair will be created
     * @param string $name the name of the keypair
     * @param string $owner the owner used to tag the keypair
     * @return object|false returns the keypair if everything goes well, otherwise false
     */
    public function create_keypair($token, $azure_subscription_id, $resource_group, $location, $name, $owner) {
        $request_create_url = 'https://management.azure.com/subscriptions/'.$azure_subscription_id.'/resourceGroups/'.$resource_group.
                      '/providers/Microsoft.Compute/sshPublicKeys/'.$name.'?api-version=2023-09-01';
        
        $request_create_body = "
        {
            \"location\": \"".$location."\",
            \"tags\": {
                \"owner\": \"".$owner."\"
            }
        }";

        $request_create_header = array(
            'Authorization' => 'Bearer ' . $token->access_token,
            'Content-Type' => 'application/json'
        );

        $request_create = $this->build_request($request_create_url, HTTP_Request2::METHOD_PUT, $request_create_header, $request_create_body, null);

        $response_create = $this->send_request($request_create, false, false, true);

        if($response_create) {
            $request_generateKey_url = 'https://management.azure.com/subscriptions/'.$azure_subscription_id.'/resourceGroups/'.$resource_group.
                                        '/providers/Microsoft.Compute/sshPublicKeys/'.$name.'/generateKeyPair?api-version=2023-09-01';
            
            $request_generateKey_header = array(
                'Authorization' => 'Bearer ' . $token->access_token,
                'Content-Type' => 'application/json'
            );

            $request_generateKey = $this->build_request($request_generateKey_url, HTTP_Request2::METHOD_POST, $request_generateKey_header, null, null);

            $response_generateKey = $this->send_request($request_generateKey, false, false, true);

            return $response_generateKey;
        }
        return $response_create;
    }

     /**
     * Get details about an instance on the Azure Cloud
     *
     * @param object $token the token used to connect to the Azure cloud
     * @param string $azure_subscription_id the ID of the used subscription
     * @param string $resource_group the resource group where the instance is located
     * @param string $name the name of the instance
     * @return object|false returns the instance if everything goes well, otherwise false
     */
    public function describe_instance($token, $azure_subscription_id, $resource_group, $name) {
        $url = 'https://management.azure.com/subscriptions/'.$azure_subscription_id.'/resourceGroups/'.$resource_group.
               '/providers/Microsoft.Compute/virtualMachines/'.$name.'?api-version=2023-09-01';

        $header = array(
        'Authorization' => 'Bearer ' . $token->access_token,
        'Content-Type' => 'application/json'
        );

        $request = $this->build_request($url, HTTP_Request2::METHOD_GET, $header, null, null);

        $response = $this->send_request($request, false, false, true);

        return $response;
    }
}