<?php
defined('BASEPATH') or exit('No direct script access allowed');

class FPET extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("OracleDBM");
        $this->load->model("TrainingM");
        $this->load->model("SettingM");
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
        $data['training']   = $this->TrainingM->searchTraining(true, '', '');
        $data['substance']  = $this->TrainingM->getAllSubstances();
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
            $employee   = $this->OracleDBM->getEmpByNPK($a->AWIEMP_NPK);
            $combine = [
                'npk'       => $employee->NPK,
                'nama'      => $employee->NAMA,
                'target'      => $a->FPETFM_TARGET,
                'idFpet'      => $a->FPETFM_ID,
                'statusApproved'  => $a->FPETFM_APPROVED,  // Corrected key
                'statusApprovedHr' => $a->FPETFM_HRAPPROVED,  // Corrected key
            ];
            $getFpetData[] = $combine;
        }
        $data['fpet']   = $getFpetData;
        $data['defHR']  = $this->OracleDBM->getEmpByNPK($this->SettingM->getSettingValue('FPETFM_DEFAULTHR'));

        $this->load->view('fpet/masterFpet', $data);
    }

    public function approvalMenu()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $npk = $this->session->userdata('npk');
        $data['training']   = $this->TrainingM->searchTraining(true, '', '');
        $data['substance']  = $this->TrainingM->getAllSubstances();
        $data['employee']   = $this->OracleDBM->getAllEmp();
        $data['dept']       = $this->OracleDBM->getAllDept();
        $data['notif']        = $this->TrainingM->getNotif($npk);
        $data['tags']          = $this->AdminM->getTags();
        $data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
        $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

        $getFpetDataEmployee2    = $this->FPETM->getApprovedFpet($npk);
        $getFpetData = [];
        foreach ($getFpetDataEmployee2 as $a) {
            $employee   = $this->OracleDBM->getEmpByNPK($a->AWIEMP_NPK);
            $combine = [
                'npk'       => $employee->NPK,
                'nama'      => $employee->NAMA,
                'target'      => $a->FPETFM_TARGET,
                'idFpet'      => $a->FPETFM_ID,
                'statusApproved'  => $a->FPETFM_APPROVED,
                'statusApprovedHr' => $a->FPETFM_HRAPPROVED,
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
        $partisipanTraining = $this->input->post('partisipanTraining');
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
            'FPETFM_TRAINSUGGEST' =>  $this->input->post('trainSuggest'),
            'FPETFM_APPROVER' => $approved,
            'AWIEMP_NPK' => $partisipanTraining,
            'FPETFM_HRAPPROVER' => $approvedHr,
            'FPETFM_ACTUAL' => $actual,
            'FPETFM_TARGET' => $target,
            'FPETFM_EVAL' => $eval,
            'FPETFM_NOTES' => $notes,
            'FPETFM_STATUS' => 2,
            'FPETFM_PACTUAL' => $rActual, // Add rActual to the data array
            'FPETFM_PTARGET' => $rTarget, // Add rTarget to the data array
            'FPETFM_PEVAL' => 0,
            'FPETFM_APPROVED' => 2,
            'FPETFM_HRAPPROVED' => 2,
            'FPETFM_CREADATE'              => date('Y/m/d H:i:s'),
            'FPETFM_CREABY'                => $this->session->userdata('npk')
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

    // public function rejectFpet($id)
    // {

    //     if (!$this->isAllowed()) return redirect(site_url());
    //     $this->FPETM->rejectApproveFpet($id, 0);
    //     redirect(site_url('FPET/approvalMenu'));
    // }

    // public function approveFpet($id)
    // {
    //     if (!$this->isAllowed()) return redirect(site_url());
    //     $this->FPETM->rejectApproveFpet($id, 1);
    //     redirect(site_url('FPET/approvalMenu'));
    // }

    public function rejectApproveFpet($code, $id)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        if ($code == 1) {
            $this->FPETM->rejectApproveFpet($id, $code);
        } elseif ($code == 0) {
            $this->FPETM->rejectApproveFpet($id, $code);
        } elseif ($code == 3) {
            $this->FPETM->rejectApproveHrFpet($id, 0);
        }
        redirect(site_url('FPET/approvalMenu'));
    }


    // public function rejectHrFpet($id)
    // {
    //     if (!$this->isAllowed()) return redirect(site_url());
    //     $this->FPETM->rejectApproveHrFpet($id, 0);

    //     redirect(site_url('FPET/approvalMenu'));
    // }

    public function approveHrFpet($id)
    {
        if (!$this->isAllowed()) return redirect(site_url());

        $participant = $this->input->post('npk');
        print_r($participant);
        $rEstablished = $this->input->post('rEstablished');
        $chooseTrain = $this->input->post('chooseTrain');
        $title = $this->input->post('title');
        $educator = $this->input->post('educator');
        $schedule = $this->input->post('schedule');
        $cost = $this->input->post('cost');
        $idFpet = $this->input->post('idFpet');

        $this->load->model('FPETM');

        $data = array(
            // Add data from the form fields

            //  'chooseTrain' => $chooseTrain,
            'TRNHDR_TITLE' => $title,
            'TRNHDR_INSTRUCTOR' => $educator,
            'TRNHDR_SCHEDULE' => $schedule,
            'TRNHDR_COST' => $cost,
            //  'idFpet' => $idFpet,
            'TRNHDR_CATEGORY' => $this->input->post('categoryTrain'),
            'TRNHDR_MODIDATE' => date('Y/m/d H:i:s'),
            'TRNHDR_MODIBY' => $this->session->userdata('npk'),
            'TRNHDR_STATUS' => 1
        );
        $lastInsertedId = 0;

        if ($rEstablished == 1) {

            $lastInsertedId = $chooseTrain;
        } else {
            $this->FPETM->makeTrain($data);
            $lastInsertedId = $this->db->insert_id();
        }
        print_r($lastInsertedId);
        $data3 = array(
            'AWIEMP_NPK' => $participant,
            'TRNHDR_ID' => $lastInsertedId,
            'TRNACC_PERMISSION' => 1,
        );
        $this->FPETM->addParticipantTraining($data3);


        $this->FPETM->rejectApproveHrFpet($id, 1, $lastInsertedId, $rEstablished);
        redirect(site_url('FPET/approvalMenu'));
    }

    public function publishFpet($id)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $this->FPETM->publishFpet($id);
        redirect(site_url('FPET'));
    }
    public function checkParticipant()
    {
        $participant = $this->input->get('npk');
        $chooseTrain = $this->input->get('id');
        header('Content-Type: application/json');
        echo json_encode($this->FPETM->checkParticipant($participant, $chooseTrain));
        return $this->FPETM->checkParticipant($participant, $chooseTrain);
    }

    public function modifyFpet()
    {
        // Retrieve form data from POST request
        $approved = $this->input->post('approved');
        $partisipanTraining = $this->input->post('partisipanTraining');
        $approvedHr = $this->input->post('approvedHr');

        // Retrieve other form data
        $actual = $this->input->post('actual');
        $trainSuggest = $this->input->post('trainSuggest');
        $target = $this->input->post('target');
        $eval = $this->input->post('eval');
        $notes = $this->input->post('notes');
        $rActual = $this->input->post('rActual');
        $rTarget = $this->input->post('rTarget');
        $rEval = $this->input->post('rEval');
        $idFpet = $this->input->post('idFpet');
        $this->load->model('FPETM');
        $data = array(
            'FPETFM_APPROVER' => $approved,
            'FPETFM_TRAINSUGGEST' => $trainSuggest,
            'AWIEMP_NPK' => $partisipanTraining,
            'FPETFM_HRAPPROVER' => $approvedHr,
            'FPETFM_ACTUAL' => $actual,
            'FPETFM_TARGET' => $target,
            'FPETFM_EVAL' => $eval,
            'FPETFM_NOTES' => $notes,
            'FPETFM_STATUS' => 2,
            'FPETFM_PACTUAL' => $rActual, // Add rActual to the data array
            'FPETFM_PTARGET' => $rTarget, // Add rTarget to the data array
            'FPETFM_PEVAL' => 0,
            'FPETFM_APPROVED' => 2,
            'FPETFM_HRAPPROVED' => 2,
            'FPETFM_MODIDATE'             => date('Y/m/d H:i:s'),
            'FPETFM_MODIBY'               => $this->session->userdata('npk')
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
