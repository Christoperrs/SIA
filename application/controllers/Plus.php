<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Plus extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model("OracleDBM");
        $this->load->model("AdminM");
        $this->load->model("ChartM");
        $this->load->model("TrainingM");
        $this->load->helper(array('form', 'url'));
		$this->load->library('session');
        $this->load->library('form_validation');
	}

    public function isAllowed() {
        return $this->session->userdata('isLogin') && $this->session->userdata('role') == 'admin';
    }    

    public function searchEmployee()
	{
        // $name   = $this->input->post('search_employee');
		// $dept   = $this->input->post('code');
		// if ($code == 'name')
        $filteredData['employees']     = $this->OracleDBM->getEmployeeByKeyword();
        // else if ($code == 'dept') $filteredData['employees']= $this->OracleDBM->getEmpByNPK('NM_SIE', $keyword);
		// else $filteredData['employees']                     = $this->OracleDBM->getAllEmp();
        // return $filteredData;
        header('Content-Type: application/json');
        echo json_encode($filteredData);
	}
    
	public function searchTraining() {
		$isAll	= filter_var($this->input->post('isAll'), FILTER_VALIDATE_BOOLEAN);
		$key	= $this->input->post('keyword');
        $tag	= $this->input->post('tag');
		echo json_encode($this->TrainingM->searchTraining($isAll, $key, $tag));
	}

    public function filterTraining() {
		$status	= $this->input->post('status');
		echo json_encode($this->TrainingM->filterTraining($status));
	}

	public function getAccessData()
	{
		$npk = $this->input->get('npk');
        $id = $this->input->get('id');
		header('Content-Type: application/json');
        echo json_encode($this->TrainingM->getAccessData($npk, $id));
	}

    public function modifyAccess() {
        $code = $this->input->post('code');
        $value = $this->input->post('value');
		$this->TrainingM->modifyAccess($code, $value);
	}

    public function hasRead($id) {
        header('Content-Type: application/json');
        echo json_encode($this->TrainingM->hasRead($id));
        return $this->TrainingM->hasRead($id);
    }

    public function getEmp($npk) {
        echo json_encode($this->OracleDBM->getEmpByNPK($npk));
    }

    public function getAdmins()
	{
        echo json_encode($this->AdminM->getAdmins());
	}

    public function allEmp()
	{
        print_r($this->OracleDBM->getAllEmp());
	}

    public function getDatas() {
        print_r($this->OracleDBM->getAllEmp());
    }
}