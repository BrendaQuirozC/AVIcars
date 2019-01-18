<?php

/**
 * @Author: Erik Viveros
 * @Date:   2018-07-02 15:09:06
 * @Last Modified by:   erikfer94
 * @Last Modified time: 2018-09-25 15:56:00
 */
class Excepcion extends Exception{
	public function __construct($type, $message, $code = 0, Exception $previous = null){
		$errorFile=__DIR__."/../../../error/$type.log";
		$error=$_SERVER["REQUEST_URI"].": ".$message;
		$fp=fopen($errorFile, "a");
		fputs($fp,$error."\n");
		fclose($fp);
		parent::__construct($message, $code, $previous);
	}
}