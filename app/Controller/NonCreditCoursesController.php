<?php
App::uses('AppController', 'Controller');
class NonCreditCoursesController extends AppController {
/**
	public $components = array('Paginator', 'Flash', 'Session');
/**
	public function index() {
			$results = $this->NonCreditCourse->find('all');
	}
/**
	public function view($id = null) {
			if (!$this->NonCreditCourse->exists($id)) {
	}
/**
	public function add() {
			if ($this->request->is('post')) {
	}
/**
	public function edit($id = null) {
			if (!$this->NonCreditCourse->exists($id)) {
		$this->layout=false;
	}
/**
	public function delete($id = null) {
			$this->NonCreditCourse->id = $id;
}