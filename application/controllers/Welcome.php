<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('Object_model');
	}

	public function index()
	{
		print("<pre>".print_r($this->Object_model->findWhereRawFirst('slug = "carnation" or id = 4'),true)."</pre>");
	}
}
