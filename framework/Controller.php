<?php
namespace core;


class Controller extends mvc
{

	public function getParam($target){
		return self::$Request[$target];
	} 

	public function getParams(){
		return self::$Request;
	} 

	public function getBody(){
		return self::$Request["data"];
	} 	

	public function getBodyAttr($target){
		return self::$Request["data"][$target];
	} 

}