<?php
class Controller_Index extends Controller_Abstract{

	protected $_controller = 'index';
	
	public function indexAction(){
		header("Location:index/home");
	}
	
	public function homeAction(){
		
	}

}