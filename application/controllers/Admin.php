<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Admin extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model("OracleDBM");
        $this->load->model("AdminM");
        $this->load->model("TrainingM");
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $admins = $this->AdminM->getAdmins();

        $detailEmployee = [];
        foreach ($admins as $adm) {
            $detailEmployee[] = $this->OracleDBM->getEmpByNPK($adm->AWIEMP_NPK);
        }
        usort($detailEmployee, function($a, $b) {
            return strcmp($a->NAMA, $b->NAMA);
        });

        $npk = $this->session->userdata('npk');
        $data['admin']      = $detailEmployee;
        $data['tags']       = $this->AdminM->getTags();
        $data['countAdmin'] = $this->AdminM->getAdminTotal();
        $data['employee']   = $this->OracleDBM->getAllEmp();
        $data['dept']       = $this->OracleDBM->getAllDept();
        $data['notif']      = $this->TrainingM->getNotif($npk);

        $data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
        $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
        foreach ($data['employee'] as &$employee) {
            $employee->isAdmin = $this->AdminM->isNpkAdmin($employee->NPK);
        }

        $this->load->view('admin_index', $data);
    }

    public function isAllowed()
    {
        return $this->session->userdata('isLogin') && $this->session->userdata('role') == 'admin';
    }

    public function getAdmins()
    {
        echo json_encode($this->AdminM->getAdmins());
    }

    public function saveAdmin()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $admins = json_decode($this->input->post('empSelected'));
        if (!empty($admins)) {
            foreach ($admins as $admin) {
                $data = array(
                    'AWIEMP_NPK'        => $admin,
                    'ADMAPP_STATUS'     => 1,
                    'ADMAPP_CREADATE'   => date('Y/m/d H:i:s'),
                    'ADMAPP_CREABY'     => $this->session->userdata('npk'),
                    'ADMAPP_MODIDATE'   => date('Y/m/d H:i:s'),
                    'ADMAPP_MODIBY'     => $this->session->userdata('npk'),
                );
                $this->AdminM->saveAdmin($data);
            }
        }
        redirect('Admin');
    }

    public function deleteAdmin($npk)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $data = array(
            'ADMAPP_STATUS'     => 0,
            'ADMAPP_MODIBY'     => $this->session->userdata('npk'),
            'ADMAPP_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'AWIEMP_NPK'        => $npk
        );
        $this->AdminM->deleteAdmin($data, $where);
        redirect(site_url('Admin'));
    }

    public function saveTag()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $data = array(
            'TRNLBL_NAME'       => $this->input->post('nameTag'),
            'TRNLBL_COLOR'      => $this->input->post('colorTag'),
            'TRNLBL_STATUS'     => 1,
            'TRNLBL_CREADATE'   => date('Y/m/d H:i:s'),
            'TRNLBL_CREABY'     => $this->session->userdata('npk'),
            'TRNLBL_MODIDATE'   => date('Y/m/d H:i:s'),
            'TRNLBL_MODIBY'     => $this->session->userdata('npk'),
        );
        $this->AdminM->saveTag($data);
        redirect(site_url('Admin'));
    }

    public function deleteLabel($id)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $count = $this->AdminM->getLabelTotal($id);
        print_r($count);
        if ($count <= 0) {
            $data = array(
                'TRNLBL_STATUS'     => 0,
                'TRNLBL_MODIBY'     => $this->session->userdata('npk'),
                'TRNLBL_MODIDATE'   => date('Y/m/d H:i:s'),
            );
            $where = array(
                'TRNLBL_ID' => $id
            );
            $this->AdminM->deleteLabel($data, $where);
        }
        redirect(site_url('Admin'));
    }
}
