<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Training extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("OracleDBM");
		$this->load->model("TrainingM");
		$this->load->model("QuestionM");
		$this->load->model("AdminM");
		$this->load->helper(array('form', 'url'));
		$this->load->library('session');
		$this->load->library('form_validation');
	}

	public function index()
	{
		if (!$this->isAllowed()) return redirect(site_url());
		$npk = $this->session->userdata('npk');
		$data['training']   = $this->TrainingM->searchTraining(true, '', '');
		$data['substance']  = $this->TrainingM->getAllSubstances();
		$data['employee']   = $this->OracleDBM->getAllEmp();
		$data['dept']       = $this->OracleDBM->getAllDept();
		$data['notif']		= $this->TrainingM->getNotif($npk);
		$data['tags']  		= $this->AdminM->getTags();
		$data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
		$data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
		$this->load->view('training/training_index', $data);
	}

	public function isAllowed()
	{
		return $this->session->userdata('isLogin');
	}

	public function showDetail($id)
	{
		$npk = $this->session->userdata('npk');
		$data["header"] = $this->TrainingM->getTrainingHeader($id);
		$data["pretest"] = $this->TrainingM->checkPreTest($npk, $id);
		$emps           = $this->TrainingM->getParticipantByTraining($id);
		$data["package"]  = $this->QuestionM->getpackageQuest($id);
		$detailEmployeeE = [];
		foreach ($emps as $emp) {
			$employee   = $this->OracleDBM->getEmpByNPK($emp->AWIEMP_NPK);
			$prog       = $this->TrainingM->getProgress($id, $emp->AWIEMP_NPK);
			$combinedData = [
				'NPK'       => $employee->NPK,
				'NAMA'      => $employee->NAMA,
				'DEPARTEMEN' => $employee->DEPARTEMEN,
				'PERCENT'   => $prog->percentage,
				'PROGRESS'  => $prog->progress,
				'STATUS'	=> $this->TrainingM->getAccessByNPKID($employee->NPK, $id)->TRNACC_PERMISSION,

			];
			$detailEmployeeE[] = $combinedData;
		}

		$data["substance"]  = $this->TrainingM->getSubstanceByTraining($id);
		$data["packageQuest"]  = $this->TrainingM->getPackageByTraining($id);
		$data["employee"]   = $detailEmployeeE;
		$data["tags"]   	= $this->AdminM->getTagsByID($id);
		$data["resume"]  = $this->TrainingM->getResumePersonal($npk, $id);
		echo json_encode($data);
	}

	public function modifyTraining($str)
	{
		// Publish/Delete training
		$id = substr($str, 0, strlen($str) - 1);
		$code = substr($str, -1);
		$this->TrainingM->modifyTraining($id, $code);
		redirect('Training');
	}

	public function saveTraining()
	{
		// Saving new training
		if (!$this->isAllowed()) return redirect(site_url());

		// Saving training
		$this->TrainingM->saveTraining();
		$lastInsertedId = $this->db->insert_id();

		$count = 0;
		foreach ($this->input->post() as $key => $value) {
			if (strpos($key, 'materiTitle') !== false) {
				$count++;
			}
		}
		// foreach ($this->input->post() as $key => $value) {
		// 	if (strpos($key, 'materiTitle') !== false && $this->input->post('materiFile' . substr($key, 10)) !== null) {
		// 		$count++;
		// 	} else if(strpos($key, 'materiTitle') !== false){
		// 		$count++;
		// 	}else if($this->input->post('materiFile' . substr($key, 10)) !== null){
		// 		$count++;
		// 	}
		// }

		// Saving each substance
		for ($i = 1; $i <= $count; $i++) {
			$judulMateri = $this->input->post('materiTitle' . $i);

			if ($judulMateri !== null) {
				$config['upload_path']   = './uploads/';
				$config['allowed_types'] = 'pdf';
				$config['max_size']      = 10240;

				$this->load->library('upload', $config);

				if ($this->upload->do_upload('materiFile' . $i)) {
					$fileData = $this->upload->data();
					$fileName = $fileData['file_name'];
					$filePath = 'uploads/' . $fileName;

					$this->TrainingM->saveSubstance($filePath, $judulMateri, $lastInsertedId);
				} else {
					echo $this->upload->display_errors();
				}
			}
		}

		// Saving each participant
		$checkedCheckboxes = $this->input->post('chkBoxemp');
		if (!empty($checkedCheckboxes)) {
			foreach ($checkedCheckboxes as $checkbox) {
				$npks =  (string)$checkbox;
				$ids = $lastInsertedId;
				$this->TrainingM->saveParticipant($npks, $ids);
			}
		}

		// Saving each label
		$tags = json_decode($this->input->post('tags'));
		if (!empty($tags)) {
			foreach ($tags as $tag) {
				$this->TrainingM->saveLabelDetail($tag, $lastInsertedId);
			}
		}
		redirect('Training');
	}

	public function editTraining($id)
	{
		if (!$this->session->userdata('isLogin')) {
			return redirect('Login');
		} else {
			// $checkStatus = $this->TrainingM->checkStatustrain($id);
			$this->TrainingM->modifyTrainingHeader();
			$employees = $this->input->post('empSelected');
			if (!empty($employees)) {
				$id =	$this->input->post('idTraining');

				$this->TrainingM->resetParticipant(json_decode($employees), $id);
				foreach (json_decode($employees) as $employee) {
					$getId = $this->TrainingM->getAccessByNPKID($employee, $id);
					if ($getId == null) {
						$this->TrainingM->saveParticipant($employee, $id);
					} else {
						$this->TrainingM->modifyParticipant($employee, $id);
					}
				}
			}

			$tags = json_decode($this->input->post('tags'));
			$this->TrainingM->resetLabels($tags, $this->input->post('idTraining'));
			if (!empty($tags)) {
				$id =	$this->input->post('idTraining');
				foreach ($tags as $tag) {
					if (!$this->TrainingM->isLabelExist($tag, $id)) $this->TrainingM->saveLabelDetail($tag, $id);
				}
			}

			$idDetail = $this->TrainingM->getSubstanceByTraining($id);
			$materiIdArray = array();

			foreach ($this->input->post() as $key => $value) {
				if (strpos($key, 'materiId') !== false) {
					$materiIdArray[] = $value;
				}
			}

			foreach ($idDetail as $detail) {
				if (!in_array($detail->TRNSUB_ID, $materiIdArray)) {
					$this->TrainingM->modifySubstance($detail->TRNSUB_ID);
				}
			}
			$count = 0;
			foreach ($this->input->post() as $key => $value) {
				if (strpos($key, 'materiTitle') !== false) {
					$count++;
				}
			}

			if ($count > 0) {
				$index = 1;
				$counter = 1;

				// Count the number of 'materiTitle' keys


				// Find the starting index where 'materiTitle' key exists
				while (!array_key_exists('materiTitle' . $counter, $this->input->post())) {
					$index++;
					$counter++;
				}

				// Loop through 'materiTitle' keys
				for ($i = $index; $i <= $count + $index - 1; $i++) {
					echo "<script>console.log('aa + $i');</script>";

					// Assuming you want to concatenate the string, use "." instead of "+"
					print_r($i . "aa");

					$judulMateri = $this->input->post('materiTitle' . $i);
					print_r($judulMateri . "bb");

					if ($judulMateri !== null) {
						echo "<script>console.log('bb');</script>";
						print_r($judulMateri . "cc");

						$config['upload_path']   = './uploads/';
						$config['allowed_types'] = 'pdf';
						$config['max_size']      = 10240;

						$this->load->library('upload', $config);

						if ($this->upload->do_upload('materiFile' . $i)) {
							$fileData = $this->upload->data();
							$fileName = $fileData['file_name'];
							$filePath = 'uploads/' . $fileName;

							$this->TrainingM->saveSubstance($filePath, $judulMateri, $id);
						} else {
							echo $this->upload->display_errors();
						}
					}
				}
			}
			redirect('Training');
		}
	}

	public function modifyApproval()
	{
		$npk		= $this->input->post('npk');
		$status		= $this->input->post('status');
		$this->TrainingM->modifyApproval($npk, $status);
	}

	public function addProgress($id)
	{
		if (!$this->session->userdata('isLogin')) {
			return redirect('Login');
		} else {
			$this->TrainingM->saveProgress($id);
		}
	}

	public function removeNotif()
	{
		// Check if the request is an AJAX request
		// Get the 'id' and 'npk' parameters from POST data
		$id = $this->input->post('id');
		$npk = $this->input->post('npk');
		echo "<script>console.log('aa + $id' + $npk);</script>";
		// Call the removeNotif method from your model
		$this->TrainingM->removeNotif($id, $npk);

		// Return a success message to the client
		echo json_encode(['status' => 'success', 'message' => 'Notification removed successfully.']);
	}

	public function removeNotifMateri($id)
	{
		// Log a message to the CodeIgniter log file
		log_message('info', 'Notification removed successfully. ID: ' . $id);
		if (!$this->session->userdata('isLogin')) {
			return redirect('Login');
		} else {
			$this->TrainingM->removeNotifMateri($id);
		}
	}


	public function modifyResume($idHeader)
	{

		// Retrieve form data from POST request
		$textResume = $this->input->post('textResume');

		$data = array(
			'TRNACC_RESUME'		=> $textResume,
			'TRNACC_MODIDATE'   => date('Y/m/d H:i:s'),
			'TRNACC_MODIBY'     => $this->session->userdata('npk')
		);

		$saved = $this->TrainingM->modifyResume($data, $idHeader);

		redirect(site_url('Training'));
	}
	public function checkPreTest($idHeader)
	{
		$npk = $this->session->userdata('npk');
		$data['pretest'] = $this->TrainingM->checkPreTest($npk, $idHeader);

		echo json_encode($data);
	}

	public function updateTRNPCK()
	{
		$AWIEMP_NPK = $this->input->post('AWIEMP_NPK');
		$TRNHDR_ID = $this->input->post('TRNHDR_ID');
		$data = array(
			'TRNPCK_ID_PRE' 	=> $this->input->post('TRNPCK_ID'),
			'TRNPCK_ID_POST' 	=> $this->input->post('TRNPCK_ID'),
			'TRNACC_MODIDATE'   => date('Y/m/d H:i:s'),
			'TRNACC_MODIBY'     => $this->session->userdata('npk')
		);
		$this->QuestionM->savePreExam($data, $AWIEMP_NPK, $TRNHDR_ID);
	}
}
