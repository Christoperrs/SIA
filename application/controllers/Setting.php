<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Setting extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("SettingM");
        $this->load->model("TrainingM");
        $this->load->model("OracleDBM");
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $npk = $this->session->userdata('npk');
        $data['notif']      = $this->TrainingM->getNotif($npk);
        $data['notifMateri']= $this->TrainingM->getNotifMateri($npk);
        $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);
        $data['settings']   = $this->SettingM->getSettings();
        $data['employee']   = $this->OracleDBM->getAllEmp();
        $this->load->view('setting_view', $data);
    }

    public function isAllowed()
    {
        return $this->session->userdata('isLogin') && $this->session->userdata('role') == 'admin';
    }

    public function editSettings()
    {
        foreach ($this->input->post() as $settingKey => $settingValue) {
            $existingSetting = $this->SettingM->getSettingValue($settingKey);
    
            if ($existingSetting != $settingValue) {
                $data = array(
                    'SETTING_VALUE'     => $settingValue,
                    'SETTING_MODIBY'    => $this->session->userdata('npk'),
                    'SETTING_MODIDATE'  => date('Y-m-d H:i:s'),
                );
                $where = array(
                    'SETTING_KEY'   => $settingKey,
                );

                $this->SettingM->editSetting($data, $where);
            }
        }
        redirect(site_url('Setting'));
    }
}
