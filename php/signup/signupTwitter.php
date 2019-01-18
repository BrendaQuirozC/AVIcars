<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-03-22 11:03:27
 * @Last Modified by:   Erik Viveros
 * @Last Modified time: 2018-11-20 13:49:55
 */
include ($_SERVER['DOCUMENT_ROOT']).'/php/config.php';
require_once $_SERVER["DOCUMENT_ROOT"]."/libraries/twitteroauth/autoload.php";
require_once ($_SERVER['DOCUMENT_ROOT']) . '/php/usuario.php';
use Abraham\TwitterOAuth\TwitterOAuth;
class Autehtication extends Usuario{
	protected $login=null;
	private $token=null;
	private $consumer_key=null;
	private $consumer_secret=null;
	private $redirect=null;
	private $url=null;

	function __construct($consumer_key=null,$consumer_secret=null,$redirect=null,$token=null,$token_secret=null){
		$this->consumer_key=$consumer_key;
		$this->consumer_secret=$consumer_secret;
		$this->redirect=$redirect;
		if($token)
		{
			$this->token=array(
				"oauth_token"=>$token,
				"oauth_token_secret"=>$token_secret
			);
			$this->login = new TwitterOAuth($this->consumer_key, $this->consumer_secret,$this->token["oauth_token"],$this->token["oauth_token_secret"]);
		}
		else
		{
			$this->login = new TwitterOAuth($this->consumer_key, $this->consumer_secret);
		}
	}
	private function getToken(){
		$this->token = $this->login->oauth("oauth/request_token", array("oauth_callback" => $this->redirect));
	}
	private function redirect(){
		header("Location: ".$this->url);
	}
	public function connect(){
		if(!$this->token)
		{
			$this->getToken();
		}
		$this->url = $this->login->url("oauth/authenticate", array("oauth_token" => $this->token["oauth_token"]));
		//var_dump($this->url);
		//$this->login->oauth("oauth/access_token",["oauth_verifier" => $_GET["oauth_verifier"]]);
		//print_r($this->login);
		session_start();
		$_SESSION["tokenTwitter"]=$this->token;
		$this->redirect();
	}
	public function verifyConnection($verify){
		return $this->login->oauth("oauth/access_token", array("oauth_verifier" => $verify));
	}
	public function getCredentials(){
		return $this->login->get('account/verify_credentials',array('include_email' => "true", 'include_entities' => "false", 'skip_status' => "true"));
	}
}