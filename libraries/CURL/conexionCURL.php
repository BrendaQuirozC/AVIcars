<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-03-22 11:05:36
 * @Last Modified by:   BrendaQuiroz
 * @Last Modified time: 2019-01-09 11:07:24
 */
class conexionCURL{
	public $response=null;
	public $error=null;
	public $url=null;
	public $request="GET";
	public $fields=null;
	public $headers=null;
	private $curl=null;
	public $dataInnerType="json";
	public $encoding="";

	public function __construct($url=null,$request="GET",$fields=null,$headers=null,$dataInnerType="json",$encoding=""){
		$this->url=$url;
		$this->request=$request;
		$this->headers=$headers;
		$this->fields=$fields;
		$this->dataInnerType=$dataInnerType;
		$this->encoding=$encoding;
	}
	public function callService(){
		switch ($this->request) {
			case 'POST':
				$this->callPostService();
				break;
			
			default:
				$this->request="POST";
				$this->callPostService();
				break;
		}
	}
	public function callPostService(){
		$this->startCurl();
		$this->setCurlPOST();
		$this->sendCurl();
		$this->closeCurl();
	}
	private function startCurl(){
		$this->curl = curl_init();
	}
	private function setCurlPOST(){
		$data=$this->serializeData($this->fields);
		curl_setopt_array($this->curl, array(
			CURLOPT_URL => $this->url,
			CURLOPT_SSL_VERIFYHOST => 0,
			CURLOPT_SSL_VERIFYPEER => 0,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => $this->encoding,
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 30,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => $this->request,
			CURLOPT_POSTFIELDS => $data,
			CURLOPT_HTTPHEADER => $this->headers,
		));
	}
	private function sendCurl(){
		$this->response = curl_exec($this->curl);
		$this->error = curl_error($this->curl);
	}
	private function closeCurl(){
		curl_close($this->curl);
	}
	public function setVars($url=null,$request="GET",$fields=array(),$headers=array(),$dataInnerType="json",$encoding=""){
		$this->url=$url;
		$this->request=$request;
		$this->headers=$headers;
		$this->fields=$fields;
		$this->dataInnerType=$dataInnerType;
		$this->encoding=$encoding;
	}
	private function serializeData($fields){
		$dataSerialized=null;
		switch ($this->dataInnerType) {
			case 'json':
				$dataSerialized=json_encode($fields);
				break;
			
			default:
				$dataSerialized="{}";
				break;
		}
		return $dataSerialized;
	}
}