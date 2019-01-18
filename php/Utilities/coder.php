<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-07-06 09:59:16
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-11-16 09:35:55
 */
class Coder{
	public $encoded=null;
	public $toEncode=null;
	private $toChange=array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F","=");
	private $change = array("T","Q","X","M","Z","N","G","I","K","J","L","O","H","R","S","F","W");
	private $toChangeDos=array("TT","QQ","XX","MM","ZZ","NN","GG","II","KK","JJ","LL","OO","HH","RR","SS","FF","W");
	private $changeDos = array("15","14","13","12","11","10","9","8","7","6","5","4","3","2","1","0","W");
	public function __construct($cadena="0"){
		if($cadena){
			$this->toEncode=$cadena;
			$this->encode($cadena);
		}
	}
	public function encode($cadena){
		if(!is_numeric($cadena)){
			$this->encoded=$cadena;
		}
		else{
			$cadena=dechex($cadena);
			$cadena=str_pad($cadena, 10, "0", STR_PAD_LEFT);
			$newString=str_replace($this->toChange, $this->change, $cadena);
			$newString=str_replace($this->toChangeDos, $this->changeDos, $newString);
			$this->encoded=base64_encode($newString);		
		}
		return $this->encoded;
	}
	public function decode($encoded){
		$this->encoded=$encoded;
		$oldString=str_replace($this->changeDos, $this->toChangeDos, base64_decode($encoded));
		$oldString=str_replace($this->change, $this->toChange, $oldString);
		$inDec=hexdec($oldString);
		$inHex=dechex($inDec);
		$inHex=str_pad($inHex, 10, "0", STR_PAD_LEFT);
		if($oldString==$inHex){
			$this->toEncode=(int) $inDec;
		}
		return $this->toEncode;
	}
}