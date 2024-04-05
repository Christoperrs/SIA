<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Personal extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("OracleDBM");
		$this->load->model("ChartM");
		$this->load->model("FPETM");
		$this->load->model("AdminM");
		$this->load->model("TrainingM");
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
	}
	public function index()
	{
		if (!$this->isAllowed()) return redirect(site_url());
		$npk = $this->session->userdata('npk');
		$data['getCountTraining']		= $this->ChartM->getCountTraining();
		$data['getCountSubstance']		= $this->ChartM->getCountSubstance();
		$data['getCountMyTraining']   	= $this->ChartM->getCountMyTraining();
		$data['getCountMySubstance']   	= $this->ChartM->getCountMySubstance();
		$data['getCountMyDoneLesson']   = $this->ChartM->getCountMyDoneLesson();
		$data['getCountMyNotDone']   	= $this->ChartM->getCountMyNotDone();
		$data['getCountMyDonePercent']  = $this->ChartM->getCountMyDonePercent();
		$data['getMyTrendAccess']   	= $this->ChartM->getMyTrendAccess();
		$data['getFavoriteSubstance']   = $this->ChartM->getFavoriteSubstance();
		$data['getHighestEmployee']   	= $this->ChartM->getHighestEmployee();
		$data['getFavoriteTraining']   	= $this->ChartM->getFavoriteTraining();
		$data['getNotDoneLesson']   	= $this->ChartM->getNotDoneLesson();
		$data['getNotOpenTrain']   		= $this->ChartM->getNotDoneEmployee();
		$data['notif']					= $this->TrainingM->getNotif($npk);
		$data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
		$data['getNotifRejectApproveFPET']   = $this->TrainingM->getNotifRejectApproveFPET($npk);
		$data['totalNotif'] = count($data['notif']) + count($data['notifMateri']) + count($data['getNotifRejectApproveFPET']);

		$this->load->view('training/training_me', $data);
	}

	public function isAllowed()
	{
		return $this->session->userdata('isLogin');
	}

	public function Overview()
	{
		$npk = $this->session->userdata('npk');
		$data['overview']	= $this->FPETM->getOverview($this->input->post('AWIEMP_NPK'), $this->input->post('TRNHDR_ID'));
		$data['employee']	= $this->OracleDBM->getEmpByNPK($data['overview']->AWIEMP_NPK);
		$data['approver']	= $this->OracleDBM->getEmpByNPK($data['overview']->FPETFM_APPROVER);
		$data['HRapprover']	= $this->OracleDBM->getEmpByNPK($data['overview']->FPETFM_HRAPPROVER);
		$data['evaluator']	= $this->OracleDBM->getEmpByNPK($data['overview']->FPETFM_CREABY);
		$data['notif']		= $this->TrainingM->getNotif($npk);
		$data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
		$data['getNotifRejectApproveFPET']   = $this->TrainingM->getNotifRejectApproveFPET($npk);
		$data['totalNotif'] = count($data['notif']) + count($data['notifMateri']) + count($data['getNotifRejectApproveFPET']);
		echo json_encode($data);
	}

	public function Resumes()
	{
		$npk = $this->session->userdata('npk');
		$resumes	= $this->FPETM->getResumes();
		$getHighest = [];
		foreach ($resumes as $a) {
			$employee   = $this->OracleDBM->getEmpByNPK($a->AWIEMP_NPK);
			$combine = [
				'NPK'       	=> $employee->NPK,
				'NAMA'			=> $employee->NAMA,
				'DEPARTEMEN'	=> $employee->DEPARTEMEN,
				'FPETFM_ID'		=> $a->FPETFM_ID,
				'TRNHDR_TITLE'	=> $a->TRNHDR_TITLE,
				'TRNHDR_ID'		=> $a->TRNHDR_ID,
			];
			$getHighest[] = $combine;
		}
		$data['resumes']   	= $getHighest;
		$data['notif']		= $this->TrainingM->getNotif($npk);
		$data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
		$data['getNotifRejectApproveFPET']   = $this->TrainingM->getNotifRejectApproveFPET($npk);
		$data['totalNotif'] = count($data['notif']) + count($data['notifMateri']) + count($data['getNotifRejectApproveFPET']);

		$this->load->view('resume', $data);
	}

	public function evaluate()
	{
		$data = array(
			'FPETFM_EVAL' 		=> $this->input->post('FPETFM_EVAL'),
			'FPETFM_PEVAL' 		=> $this->input->post('FPETFM_PEVAL'),
			'FPETFM_STATUS'     => 3,
			'FPETFM_MODIDATE'   => date('Y/m/d H:i:s'),
			'FPETFM_MODIBY'     => $this->session->userdata('npk'),
		);

		$this->FPETM->modifyFpet($data, $this->input->post('FPETFM_ID'));
		redirect('Personal/Resumes');
	}
}
