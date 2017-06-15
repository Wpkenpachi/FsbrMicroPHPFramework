<?php
namespace core;

class Controller extends mvc
{
	public $Attrs;

	protected function getParam($target){
		return self::$Request["gets"][$target];
	} 

	protected function getParams(){
		return self::$Request["gets"];
	} 

	protected function getBody(){
		return self::$Request["data"];
	} 	

	protected function getBodyAttr($target){
		return self::$Request["data"][$target];
	} 

}