<?php
/**
 * ZAPORT API (via the JSON-RPC Zabbix API)
 * @version 0.1.0 Public Release - October 01, 2017
 * @author Leandro S. Sousa http://www.z3tasistemas.com
 * @see http://www.z3tasistemas.com/zaport_api
 *
 * Based on the Zabbix 3.0 API - The official docs are still slim...
 * @see https://www.zabbix.com/documentation/3.0/manual/api/reference
 *
 * @requires PHP 5.2 or greater
 * @requires PHP JSON functions (json_encode/json_decode)
 * @requires PHP CURL
 * @requires Zabbix 3.0 or greater (so it has the API), preferably 3.0
 *
 * @copyright 2017 Leandro S. Sousa - http://www.z3tasistemas.com
 * @license Wishlist-ware
 * --------------------------------------------------------------------------------
 * Definition of "Wishlist-ware"
 *
 * Leandro S. Sousa (http://www.z3tasistemas.com) wrote this file. As long as you retain the 
 * copyright and license you can do whatever you want with this. If you use this 
 * and it helps benefit you or your company (and you can afford it) buy me an item
 * from one of my wish lists (on my website) or if we cross paths buy me caffeine
 * in some form and we'll call it even!
 * --------------------------------------------------------------------------------*/
	//include "session.php";
	
	
	//Static function
	function setOpt($json_data_string, $method){
		include "setOpt.php";
		$url = curl_init($urlApi);
        curl_setopt($url, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($url, CURLOPT_POSTFIELDS, $json_data_string);
        curl_setopt($url, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($url, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data_string))
        );
        curl_setopt($url, CURLOPT_TIMEOUT, 5);
        curl_setopt($url, CURLOPT_CONNECTTIMEOUT, 5);

        //execute post
        $result = curl_exec($url);

        //close connection
        curl_close($url);
		return $result;
	}
	
	//Get list of hosts
	function getHosts(){
		
		$mountJsonData = array("jsonrpc" => "2.0", "method" => "host.get", 
							"params" => array(
									  "output" => array ("hostid" => "hostid", "host" => "host"),
									  "selectInterfaces" => array ("interfaceid" => "interfaceid", "ip" => "ip")
									 ),
							"id" => $_SESSION["ID"], "auth" => $_SESSION["USER"]);

        $json_data_string = json_encode($mountJsonData);		
		$result = setOpt($json_data_string, "GET");		
		return $result;
	}
	
	//Get graph of one host 
	function getGraph($idHost){
		
	$mountJsonData = array("jsonrpc" => "2.0", "method" => "graph.get", 
							"params" => array("output" => "extend", "hostids" => $idHost, "sortfield" => "name"),
							"id" => $_SESSION["ID"], "auth" => $_SESSION["USER"]
							);

        $json_data_string = json_encode($mountJsonData);		
		$result = setOpt($json_data_string, "GET");		
		return $result;
	}
	
	//Get alerts 
	function getAlert($actionId){
		$mountJsonData = array("jsonrpc" => "2.0", "method" => "alert.get", 
							"params" => array("output" => "extend", "actionids" => $actionId),
							"id" => $_SESSION["ID"], "auth" => $_SESSION["USER"]
							);

        $json_data_string = json_encode($mountJsonData);		
		$result = setOpt($json_data_string, "GET");		
		return $result;	
	}
	
	//Get login auth 
	function getAuth($login, $password){
		
		 $mountJsonData = array("jsonrpc" => "2.0", 
				"method" => "user.login", 
				"params" => array(
									"user" => $login, 
									"password" => $password
								), 
				"id" => 1, 
				"auth" => null
				);


        $json_data_string = json_encode($mountJsonData);		
		$result = setOpt($json_data_string, "POST");		
		return $result;
	}
?>