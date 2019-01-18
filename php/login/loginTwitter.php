<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-03-22 10:46:13
 * @Last Modified by:   Erik Viveros
 * @Last Modified time: 2018-11-21 17:24:30
 */
//$auth=new OAuth;

const CONSUMER_KEY = "upeRtJzdOjTSNRYyYmoaCcjhB";
const CONSUMER_SECRET = "AwKKDrCQSf8fcrsHIx60V6H51UUbw6Ty8tPg5cgPkMW5ciP6nk";
const ACCESS_TOKEN = "215832304-dRyZN0Wwm018haK3LQYVKVo8LDk1tS6BPUdqUc2f";
const TOKEN_SECRET = "LstKudL6jQmvcBCex3vII4C5a6B6bQvR7dQARIwZCvMPF";
const REDIRECT_URI = "https://avicars.app/php/login/loginTwitter.php"; //Cambiar con URL con la que se pruebe
require_once $_SERVER["DOCUMENT_ROOT"]."/php/signup/signupTwitter.php";
session_start();
if(isset($_SESSION["tokenTwitter"],$_GET["oauth_verifier"]))
{
	$login=new Autehtication(CONSUMER_KEY,CONSUMER_SECRET,REDIRECT_URI,$_SESSION["tokenTwitter"]["oauth_token"],$_SESSION["tokenTwitter"]["oauth_token_secret"]);
	$token=$login->verifyConnection($_GET["oauth_verifier"]);

	$login2=new Autehtication(CONSUMER_KEY,CONSUMER_SECRET,REDIRECT_URI,$token["oauth_token"],$token["oauth_token_secret"]);
	$credentials=$login2->getCredentials();
	$_SESSION["previuos"]=true;
	$_SESSION["mail"]=$credentials->email;
	$_SESSION["idprev"]=$credentials->id;
	$_SESSION["name"]=$credentials->name;
	$_SESSION["arroba"]=$credentials->screen_name;
	if(isset($_SESSION["redirect"])){
		header("Location: ".$_SESSION["redirect"]);
	}
	else{
		header("Location: /");
	}
}
else
{
	if(isset($_GET["u"])){
		$_SESSION["redirect"]=$_GET["u"];
	}
	$login=new Autehtication(CONSUMER_KEY,CONSUMER_SECRET,REDIRECT_URI);
	$login->connect();
}