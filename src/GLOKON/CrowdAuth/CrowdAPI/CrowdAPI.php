<?php

/*
 * This file is part of Laravel CrowdAuth
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GLOKON\CrowdAuth;

class CrowdAPI {

	/**
     * Runs the data against the Crowd RESTful API
     *
     * @param  string  $requestEndpoint
     * @param  string  $requestType
     * @param  array  $requestData
     * @return array
     */
	private function runCrowdAPI($requestEndpoint, $requestType, $requestData)
	{
		$crowdURL = \Config::get('crowd-auth::url');
		$crowdAppName = \Config::get('crowd-auth::app_name');
		$crowdAppPassword = \Config::get('crowd-auth::app_password');
		$crowdHTTPHeaders = array(
								'Accept: application/json',
								'Content-Type: application/json',
							);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $crowdURL . 'rest/usermanagement' . $requestEndpoint);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $crowdHTTPHeaders);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_USERPWD, $crowdAppName . ":" . $crowdAppPassword);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		switch($requestType) {
			case "POST":
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
				break;
			case "DELETE":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
				break;
			case "PUT":
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
				curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));
				break;
		}
		$crowdOutput = curl_exec($ch);
		$crowdHTTPStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$crowdOutputDecoded = json_decode($crowdOutput, true);
		return array('status' => $crowdHTTPStatus, 'data' => $crowdOutputDecoded);
	}


	/**
     * Authenticates user and gets SSO token
     *
     * @param  array  $credentials
     * @return string|null
     */
	public function ssoAuthUser($credentials)
	{
		$apiEndpoint = '/1/session';
		$apiData = array(
					'username' => $credentials['username'],
					'password' => $credentials['password'],
					'validation-factors' => array(
						'validationFactors' => array(
							array(
								'name' 	=> 'remote_address',
								'value' => $_SERVER['REMOTE_ADDR']
							)
						)
					));
		$apiReturn = $this->runCrowdAPI($apiEndpoint, "POST", $apiData);
		if($apiReturn['status'] == '201') {
			if($credentials['username'] == $apiReturn['data']['user']['name']) {
				return $apiReturn['data']['token'];
			}
		}
		return null;
	}


	/**
     * Retrieves user data from SSO token
     *
     * @param  string  $username
     * @param  string  $token
     * @return array|null
     */
	public function ssoGetUser($username, $token)
	{
		$apiEndpoint = '/1/session/' . $token;
		$apiData = null;
		$apiReturn = $this->runCrowdAPI($apiEndpoint, "GET", $apiData);
		if($apiReturn['status'] == '200') {
			if($apiReturn['data']['user']['name'] == $username && $token == $apiReturn['data']['token']) {
				return $this->getUser($apiReturn['data']['user']['name']);
			}
		}
		return null;
	}


	/**
     * Retrieves the token if matched with sent token
     *
     * @param  string  $token
     * @return string|null
     */
	public function ssoGetToken($token)
	{
		$apiEndpoint = '/1/session/' . $token;
		$apiData = null;
		$apiReturn = $this->runCrowdAPI($apiEndpoint, "GET", $apiData);
		if($apiReturn['status'] == '200') {
			return $apiReturn['data']['token'];
		}
		return null;
	}


	/**
     * Retrieves the token if matched with sent token
     *
     * @param  string  $token
     * @return string|null
     */
	public function ssoUpdateToken($token)
	{
		$apiEndpoint = '/1/session/' . $token;
		$apiData = array(
						'validationFactors' => array(
							'name' => 'remote_address',
							'value' => $_SERVER['REMOTE_ADDR']
						));
		$apiReturn = $this->runCrowdAPI($apiEndpoint, "POST", $apiData);
		if($apiReturn['status'] == '200') {
			return $apiReturn['data']['token'];
		}
		return null;
	}


	/**
     * Invalidates the token when logged out
     *
     * @param  string  $token
     * @return bool
     */
    public function ssoInvalidateToken($token)
	{
		$apiEndpoint = '/1/session/' . $token;
		$apiData = null;
		$apiReturn = $this->runCrowdAPI($apiEndpoint, "DELETE", $apiData);
		if($apiReturn['status'] == '204') {
			return true;
		}
		return false;
	}


	/**
     * Retrieves all user attributes and data.
     *
     * @param  string  $username
     * @return array|null
     */
	public function getUser($username)
	{
		$apiEndpoint = '/1/user?username=' . $username . '&expand=attributes';
		$apiData = null;
		$apiReturn = $this->runCrowdAPI($apiEndpoint, "GET", $apiData);
		if($apiReturn['status'] == '200') {
			$userAttributes = array();
			for($i = 0; $i < count($apiReturn['data']['attributes']['attributes']); $i++) {
				$currentAttribute = $apiReturn['data']['attributes']['attributes'][$i];
				$userAttributes[$currentAttribute['name']] = $currentAttribute['values'][0];
			}
			$userData = array(
							'key' => $apiReturn['data']['key'],
							'user-name' => $apiReturn['data']['name'],
							'first-name' => $apiReturn['data']['first-name'],
							'last-name' => $apiReturn['data']['last-name'],
							'display-name' => $apiReturn['data']['display-name'],
							'email' => $apiReturn['data']['email'],
							'attributes' => $userAttributes,
							'groups' => $this->getUserGroups($apiReturn['data']['name']),
						);
			return $userData;
		}
		return null;
	}


	/**
     * Gets all groups a user is a direct member of.
     *
     * @param  string  $username
     * @return array|null
     */
	public function getUserGroups($username)
	{
		$apiEndpoint = '/1/user/group/direct?username=' . $username;
		$apiData = null;
		$apiReturn = $this->runCrowdAPI($apiEndpoint, "GET", $apiData);
		if($apiReturn['status'] == '200') {
			$groups = array();
			for($i = 0; $i < count($apiReturn['data']['groups']); $i++) {
				$groups[] = $apiReturn['data']['groups'][$i]['name'];
			}
			return $groups;
		}
		return null;
	}


	/**
     * Checks to see if user exists by username
     *
     * @param  string  $username
     * @return bool
     */
	public function doesUserExist($username)
	{
		$apiEndpoint = '/1/user?username=' . $username;
		$apiData = null;
		$apiReturn = $this->runCrowdAPI($apiEndpoint, "GET", $apiData);
		if($apiReturn['status'] == '200') {
			return true;
		}
		return false;
	}


	/**
     * Checks to see if the user can login to the application
     *
     * @param  string  $username
     * @return bool
     */
	public function canUserLogin($username)
	{
		$userGroups = $this->getUserGroups($username);
		if(count($userGroups) > 0) {
			if(count(array_intersect($userGroups, \Config::get('crowd-auth::app_groups'))) > 0) {
				return true;
			}
		}
		return false;
	}
}