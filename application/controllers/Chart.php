<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Chart extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("OracleDBM");
		$this->load->model("ChartM");
		$this->load->model("AdminM");
		$this->load->model("TrainingM");
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
	}

	public function index()
	{
		if (!$this->isAllowed()) return redirect(site_url());
		$data['getCountTraining']   = $this->ChartM->getCountTraining();
		$data['getCountSubstance']   = $this->ChartM->getCountSubstance();
		$data['getCountDoneLesson']   = $this->ChartM->getCountDoneLesson();
		$data['getFavoriteSubstance']   = $this->ChartM->getFavoriteSubstance();
		$getHighestEmployee2    = $this->ChartM->getHighestEmployee();
		$getHighest = [];
		foreach ($getHighestEmployee2 as $a) {
			$employee   = $this->OracleDBM->getEmpByNPK($a->AWIEMP_NPK);
			$combine = [
				'npk'       => $employee->NPK,
				'nama'      => $employee->NAMA,
				'departemen' => $employee->DEPARTEMEN,
				'total'      => $a->total_progress
			];
			$getHighest[] = $combine;
		}
		$data['getHighestEmployee']   = $getHighest;
		$data['getCountNotDoneEmp']   = $this->ChartM->getCountNotDoneEmp();
		$data['getFavoriteTraining']   = $this->ChartM->getFavoriteTraining();
		$data['getNotDoneLesson']   = $this->ChartM->getNotDoneLesson();
		$data['getNotOpenTrain']   = $this->ChartM->getNotDoneEmployee();

		$npk = $this->session->userdata('npk');
		$data['notif']   = $this->TrainingM->getNotif($npk);
		$data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
		$data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
		$data['getTrendAccess']   = $this->ChartM->getTrendAccess();
		$this->load->view('training/training_chart', $data);
	}

	public function isAllowed()
	{
		return $this->session->userdata('isLogin');
	}
}
