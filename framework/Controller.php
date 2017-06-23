<?php
namespace core;

class Controller extends mvc
{

	protected function getParam($target){
		return self::$Request[$target];
	} 

	protected function getParams(){
		return self::$Request;
	} 

	protected function getBody(){
		return self::$Request["data"];
	} 	

	protected function getBodyAttr($target){
		return self::$Request["data"][$target];
	} 

}