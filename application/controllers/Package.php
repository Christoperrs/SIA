<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Package extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("QuestionM");
        $this->load->model("TrainingM");
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $npk = $this->session->userdata('npk');
        $data['package']        = $this->QuestionM->getPackages();
        $data['notif']        = $this->TrainingM->getNotif($npk);
        $data['notifMateri'] = $this->TrainingM->getNotifMateri($npk);
        $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
        print_r($data['package']);
        $this->load->view('question_package', $data);
    }

    public function isAllowed()
    {
        return $this->session->userdata('isLogin') && $this->session->userdata('role') == 'admin';
    }




    public function getPackage()
    {
    }

    public function savePackage()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $this->QuestionM->savePackage();
        redirect('Question/getPackage');
    }

    public function retrievePackage($id)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $data = $this->QuestionM->getPackage($id);
        echo json_encode($data);
    }
    public function editPackage()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $this->QuestionM->editPackage();
        redirect('Question');
    }


    public function deletePackage($id)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $result = $this->QuestionM->editPackage($id);

        if ($result) {
            echo json_encode(array('success' => true));
        } else {
            echo json_encode(array('success' => false, 'error' => 'Deletion failed.'));
        }
    }
}
