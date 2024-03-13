    <?php
    defined('BASEPATH') or exit('No direct script access allowed');

    class FPET extends CI_Controller
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->model("OracleDBM");
            $this->load->model("TrainingM");
            $this->load->model("FPETM");
            $this->load->model("AdminM");
            $this->load->helper(array('form', 'url'));
            $this->load->library('session');
            $this->load->library('form_validation');
        }

        public function index()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $npk = $this->session->userdata('npk');
            $data['training']   = $this->TrainingM->getTrainingByNPK(true, '', '');
            $data['substance']  = $this->TrainingM->getAllSubstance();
            $data['employee']   = $this->OracleDBM->getAllEmp();
            $data['dept']       = $this->OracleDBM->getAllDept();
            $data['notif']        = $this->TrainingM->getNotif($npk);
            $data['tags']          = $this->AdminM->getTags();
            $data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
            //       $data['fpet']          = $this->FPETM->getFpet();
            $getFpetDataEmployee2    = $this->FPETM->getFpet();
            $getFpetData = [];
            foreach ($getFpetDataEmployee2 as $a) {
                $employee   = $this->OracleDBM->getEmpBy('NPK', $a->trainerNpk);
                $combine = [
                    'npk'       => $employee->NPK,
                    'nama'      => $employee->NAMA,
                    'target'      => $a->target,
                    'idFpet'      => $a->idFpet,
                    'statusApproved'  => $a->statusApproved,  // Corrected key
                    'statusApprovedHr' => $a->statusApprovedHr,  // Corrected key
                ];
                $getFpetData[] = $combine;
            }
            $data['fpet']   = $getFpetData;

            $this->load->view('fpet/masterFpet', $data);
        }


        public function approvalMenu()
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $npk = $this->session->userdata('npk');
            $data['training']   = $this->TrainingM->getTrainingByNPK(true, '', '');
            $data['substance']  = $this->TrainingM->getAllSubstance();
            $data['employee']   = $this->OracleDBM->getAllEmp();
            $data['dept']       = $this->OracleDBM->getAllDept();
            $data['notif']        = $this->TrainingM->getNotif($npk);
            $data['tags']          = $this->AdminM->getTags();
            $data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
            $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

            $getFpetDataEmployee2    = $this->FPETM->getApprovedFpet($npk);
            $getFpetData = [];
            foreach ($getFpetDataEmployee2 as $a) {
                $employee   = $this->OracleDBM->getEmpBy('NPK', $a->trainerNpk);
                $combine = [
                    'npk'       => $employee->NPK,
                    'nama'      => $employee->NAMA,
                    'target'      => $a->target,
                    'idFpet'      => $a->idFpet,
                    'statusApproved'  => $a->statusApproved,
                    'statusApprovedHr' => $a->statusApprovedHr,
                ];
                $getFpetData[] = $combine;
            }
            $data['fpet']   = $getFpetData;
            $this->load->view('fpet/approvalFpet', $data);
        }

        public function isAllowed()
        {
            return $this->session->userdata('isLogin');
        }



        public function saveFpet()
        {

            // Retrieve form data from POST request
            $approved = $this->input->post('approved');
            $trainer = $this->input->post('trainer');
            $approvedHr = $this->input->post('approvedHr');
            $actual = $this->input->post('actual');
            $target = $this->input->post('target');
            $eval = $this->input->post('eval');
            $notes = $this->input->post('notes');
            $rActual = $this->input->post('rActual');
            $rTarget = $this->input->post('rTarget');
            $rEval = $this->input->post('rEval');
            // Assuming you want to save this data to the database
            // Load the model if not already loaded
            $this->load->model('FPETM');

            // Prepare data to be saved
            $data = array(
                'trainSuggest' =>  $this->input->post('trainSuggest'),
                'approved' => $approved,
                'trainerNpk' => $trainer,
                'approvedHr' => $approvedHr,
                'actual' => $actual,
                'target' => $target,
                'eval' => $eval,
                'notes' => $notes,
                'status' => 2,
                'rActual' => $rActual, // Add rActual to the data array
                'rTarget' => $rTarget, // Add rTarget to the data array
                'rEval' => $rEval,
                'statusApproved' => 2,
                'statusApprovedHr' => 2,
                'created_date'              => date('Y/m/d H:i:s'),
                'created_by'                => $this->session->userdata('npk')
            );

            $saved = $this->FPETM->saveFPET($data);

            redirect(site_url('FPET'));
        }

        public function showDetail($id)
        {
            $data["dataFpet"] = $this->FPETM->detailFpet($id);
            echo json_encode($data);
        }
        public function removeNotif()
        {
            $id = $this->input->post('id');
            $npk = $this->input->post('npk');
            echo "<script>console.log('aa + $id' + $npk);</script>";
            $this->TrainingM->removeNotif($id, $npk);
            echo json_encode(['status' => 'success', 'message' => 'Notification removed successfully.']);
        }

        public function removeFpet($id)
        {

            if (!$this->isAllowed()) return redirect(site_url());
            $this->FPETM->removeFpet($id);
            redirect(site_url('FPET'));
        }

        public function rejectFpet($id)
        {

            if (!$this->isAllowed()) return redirect(site_url());
            $this->FPETM->rejectApproveFpet($id, 0);
            redirect(site_url('FPET/approvalMenu'));
        }

        public function approveFpet($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->FPETM->rejectApproveFpet($id, 1);
            redirect(site_url('FPET/approvalMenu'));
        }

        public function rejectHrFpet($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->FPETM->rejectApproveHrFpet($id, 0);

            redirect(site_url('FPET/approvalMenu'));
        }

        public function approveHrFpet($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());

            $participant = $this->input->post('npk');

            $rEstablished = $this->input->post('rEstablished');
            $chooseTrain = $this->input->post('chooseTrain');
            $title = $this->input->post('title');
            $educator = $this->input->post('educator');
            $schedule = $this->input->post('schedule');
            $cost = $this->input->post('cost');
            $idFpet = $this->input->post('idFpet');

            $data = array(
                // Add data from the form fields

                'judul_training_header' => $title,
                'pemateri' => $educator,
                'schedule' => $schedule,
                'cost' => $cost,
                // 'idFpet' => $idFpet,
                'categoryTrain' => $this->input->post('categoryTrain'),
                'modified_date' => date('Y/m/d H:i:s'),
                'modified_by' => $this->session->userdata('npk'),
                'status' => 1
            );

            // $data2 = array(
            //     'rEstablished' => $rEstablished
            // );

            if ($rEstablished == 1) {
                $this->FPETM->addParticipantTraining($data3, $chooseTrain);
                $lastInsertedId = $chooseTrain;
            } else {
                $this->FPETM->makeTrain($data);
                $lastInsertedId = $this->db->insert_id();
                $data3 = array(
                    'npk' => $participant,
                    'id_training_header' => $chooseTrain,
                );
                if ($this->FPETM->checkParticipant($participant, $chooseTrain)) {
                    $this->FPETM->addParticipantTraining2($data3, $chooseTrain);
                }
            }
            print_r($lastInsertedId);

            $this->FPETM->rejectApproveHrFpet($id, 1, $lastInsertedId, $rEstablished);
            //     redirect(site_url('FPET/approvalMenu'));
        }

        public function publishFpet($id)
        {
            if (!$this->isAllowed()) return redirect(site_url());
            $this->FPETM->publishFpet($id);
            redirect(site_url('FPET'));
        }

        public function modifyFpet()
        {
            // Retrieve form data from POST request
            $approved = $this->input->post('approved');
            $trainer = $this->input->post('trainer');
            $approvedHr = $this->input->post('approvedHr');

            // Retrieve other form data
            $actual = $this->input->post('actual');
            $target = $this->input->post('target');
            $eval = $this->input->post('eval');
            $notes = $this->input->post('notes');
            $rActual = $this->input->post('rActual');
            $rTarget = $this->input->post('rTarget');
            $rEval = $this->input->post('rEval');
            $idFpet = $this->input->post('idFpet');
            $this->load->model('FPETM');
            $data = array(
                'trainSuggest' =>  $this->input->post('trainSuggest'),
                'approved' => $approved,
                'trainerNpk' => $trainer,
                'approvedHr' => $approvedHr,
                'actual' => $actual,
                'target' => $target,
                'eval' => $eval,
                'notes' => $notes,
                'status' => 2,
                'rActual' => $rActual, // Add rActual to the data array
                'rTarget' => $rTarget, // Add rTarget to the data array
                'rEval' => $rEval,
                'statusApproved' => 2,
                'statusApprovedHr' => 2,
                'modified_date'             => date('Y/m/d H:i:s'),
                'modified_by'               => $this->session->userdata('npk')
            );

            // Call the model function to save the data
            $saved = $this->FPETM->modifyFpet($data, $idFpet);

            redirect(site_url('FPET'));
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
    }
