<?php
class PrintController extends AppController
{
	var $name = "Print";
	var $uses = array();

	function index()
	{
		$this->autoRender = false;
		 
		$args = func_get_args();
		$url   = '/'.join('/', $args);
		 
		$Dispatcher =& new Dispatcher();
		$Dispatcher->dispatch($url, array('layout' => 'print'));
	}
}
?>