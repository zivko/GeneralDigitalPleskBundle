<?php

/*
 * This file is part of the GeneralDigital\PleskBundle
*
* (c) Zivko Sudarski <zivko@generaldigital.co.nz>
*
* This source file is subject to the MIT license that is bundled
* with this source code in the file LICENSE.
*/

namespace GeneralDigital\PleskBundle\Services;

/**
 * Plesk
 *
 * @author Zivko Sudarski <zivko@generaldigital.co.nz>
 */
class Plesk
{
    private $host;
    private $user;
    private $password;

    /**
     * Initializes Plesk
     *
     * @param string $host
     * @param string $user
     * @param string $password
     */
    public function __construct($host, $user, $password)
    {
        $this->host = $host;
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get Plesk API host
     *
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * Get Plesk API user
     *
     * @return string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get Plesk API password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Add FTP user to Plesk
     *
     * @param string $username
     * @param string $password
     *
     * @return multitype:XML mixed
     */
    public function addFTPUser($username, $password)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
                    <packet>
                    <ftp-user>
                        <add>
                            <name>'.$username.'</name>
                            <password>'.$password.'</password>
                            <home/>
                            <webspace-name>'.$this->getHost().'</webspace-name>
                        </add>
                    </ftp-user>
                    </packet>';

        return $this->makeRequest($request);
    }

    /**
     * Delete Plesk FTP user
     *
     * @param string $username
     *
     * @return multitype:XML mixed
     */
    public function deleteFTPUser($username)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
                    <packet>
                    <ftp-user>
                    <del>
                         <filter>
                             <name>'.$username.'</name>
                         </filter>
                    </del>
                    </ftp-user>
                    </packet>';

        return $this->makeRequest($request);
    }

    /**
     * Get Plesk FTP users account
     *
     * @return multitype:XML mixed
     */
    public function listFTPUsers()
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
                   <packet>
                   <ftp-user>
                   <get>
                       <filter>
                           <webspace-name>'.$this->getHost().'</webspace-name>
                       </filter>
                   </get>
                   </ftp-user>
                   </packet>';

        return $this->makeRequest($request);
    }

    /**
     * Create Plesk subdomain
     *
     * @param string $name
     *
     * @return multitype:XML mixed
     */
    public function addSubdomain($name)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
                   <packet>
                   <subdomain>
                       <add>
                           <parent>'.$this->getHost().'</parent>
                           <name>'.$name.'</name>
                           <property>
                               <name>www_root</name>
                               <value>/'.$name.'</value>
                           </property>
                           <property>
                               <name>ssl</name>
                               <value>true</value>
                           </property>
                           <property>
                               <name>php_handler_id</name>
                               <value>fastcgi</value>
                           </property>
                           <property>
                               <name>php</name>
                               <value>true</value>
                           </property>
                       </add>
                   </subdomain>
                   </packet>';

        return $this->makeRequest($request);
    }

    /**
     * Delete Plesk Subdomain
     *
     * @param string $name
     *
     * @return multitype:XML mixed
     */
    public function deleteSubdomain($name)
    {
        $request = '<?xml version="1.0" encoding="UTF-8"?>
                   <packet>
                   <subdomain>
                   <del>
                       <filter>
                           <name>'.$name.'.'.$this->getHost().'</name>
                       </filter>
                   </del>
                   </subdomain>
                   </packet>';

        return $this->makeRequest($request);
    }

    /**
     * Make API Request
     *
     * @param XML $request
     *
     * @return array
     */
    private function makeRequest($request)
    {
        $url = 'https://'.$this->getHost().':8443/enterprise/control/agent.php';

        $headers = array(
          'HTTP_AUTH_LOGIN: '.$this->getUser(),
          'HTTP_AUTH_PASSWD: '.$this->getPassword(),
          'Content-Type: text/xml'
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $request);

        $result = curl_exec($curl);
        curl_close($curl);

        return array('request'=>$request,'result'=>$result);
    }

}