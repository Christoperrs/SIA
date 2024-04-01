<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Article extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("OracleDBM");
        $this->load->model("ForumM");
        $this->load->model("TrainingM");
        $this->load->helper(array('form', 'url'));
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    public function index()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $npk =  $this->session->userdata('npk');
        $data['forum']   = $this->ForumM->getForum();
        $data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);

        $data['notif']        = $this->TrainingM->getNotif($npk);
        $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

        $this->load->view('forum', $data);
    }

    public function viewFRM()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $npk =  $this->session->userdata('npk');
        $data['forum']   = $this->ForumM->getForum();
        $data['notifMateri']   = $this->TrainingM->getNotifMateri($npk);
        $data['notif']        = $this->TrainingM->getNotif($npk);
        $data['totalNotif'] = count($data['notif']) + count($data['notifMateri']);

        $this->load->view('viewForum', $data);
    }

    public function saveFRM()
    {
        if (!$this->isAllowed()) return redirect(site_url());
        print_r($this->input->post());
        print_r($_FILES);
        $image_name = null;
        if ($_FILES['imgFRM']['error'] != UPLOAD_ERR_NO_FILE) {
            $config['upload_path']          = './uploads/';
            $config['allowed_types']        = 'gif|jpg|jpeg|png|bmp|svg|webp';
            $config['max_size']             = 3000;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('imgFRM')) {
                $error = array('error' => $this->upload->display_errors());
                print_r($error);
                return;
            }

            // Image uploaded successfully, get the file name
            $upload_data = $this->upload->data();
            $image_name = $upload_data['file_name'];
        }

        $data = array(
            'FRM_TITLE'        => $this->input->post('titleFRM'),
            'FRM_DESC'      => $this->input->post('descFRM'),
            'FRM_IMAGE'     => $image_name,
            'FRM_STATUS'     => 1,
            'FRM_CREADATE'   => date('Y/m/d H:i:s'),
            'FRM_CREABY'     => $this->session->userdata('npk'),
            'FRM_MODIDATE'   => date('Y/m/d H:i:s'),
            'FRM_MODIBY'     => $this->session->userdata('npk'),
        );
        $this->ForumM->saveFRM($data);
        redirect(site_url('Article'));
    }

    public function deleteFRM($id)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $data = array(
            'FRM_STATUS'     => 0,
            'FRM_MODIBY'     => $this->session->userdata('npk'),
            'FRM_MODIDATE'   => date('Y/m/d H:i:s'),
        );
        $where = array(
            'FRM_ID'        => $id
        );
        $this->ForumM->deleteFRM($data, $where);
        redirect(site_url('Article'));
    }

    public function searchArticles()
    {
        echo json_encode($this->ForumM->searchArticles());
    }

    public function isAllowed()
    {
        return $this->session->userdata('isLogin');
    }

    public function modifyFRM()
    {
        $image_name = null;
        $existing_image = $this->ForumM->detailFRM($this->input->post('idFRM'))->FRM_IMAGE;

        if ($_FILES['imgFRM']['error'] == UPLOAD_ERR_NO_FILE) {
            echo $existing_image . ' - ' . $this->input->post('imgTXTInput');
            if ($existing_image != $this->input->post('imgTXTInput') && !empty($existing_image)) {
                $upload_path = './uploads/';
                if (file_exists($upload_path . $existing_image)) {
                    unlink($upload_path . $existing_image);
                }
                $image_name = null;
            }
        } else {
            if (!empty($existing_image)) {
                $upload_path = './uploads/';
                if (file_exists($upload_path . $existing_image)) {
                    unlink($upload_path . $existing_image);
                }
            }

            $config['upload_path']          = './uploads/';
            $config['allowed_types']        = 'gif|jpg|png';
            $config['max_size']             = 3000;

            $this->load->library('upload', $config);

            if (!$this->upload->do_upload('imgFRM')) {
                $error = array('error' => $this->upload->display_errors());
                print_r($error);
                return;
            }

            $upload_data = $this->upload->data();
            $image_name = $upload_data['file_name'];
        }

        $idFRM = $this->input->post('idFRM');
        $data = array(
            'FRM_TITLE' =>  $this->input->post('titleFRM'),
            'FRM_DESC' =>  $this->input->post('descFRM'),
            'FRM_STATUS' => 1,
            'FRM_MODIDATE'             => date('Y/m/d H:i:s'),
            'FRM_MODIBY'               => $this->session->userdata('npk')
        );
        if ($_FILES['imgFRM']['error'] != UPLOAD_ERR_NO_FILE || ($_FILES['imgFRM']['error'] == UPLOAD_ERR_NO_FILE && $existing_image != $this->input->post('imgTXTInput'))) $data['FRM_IMAGE'] = $image_name;

        // Call the model function to save the data
        $saved = $this->ForumM->modifyFRM($data, $idFRM);

        redirect(site_url('Article'));
    }

    public function publishFRM($id)
    {
        if (!$this->isAllowed()) return redirect(site_url());
        $this->ForumM->publishFRM($id);
        redirect(site_url('Article'));
    }

    public function showDetail($id)
    {
        $data["dataFRM"] = $this->ForumM->detailFRM($id);
        echo json_encode($data);
    }
    public function showDetail2($id)
    {
        $data["dataFRM"] = $this->ForumM->detailFRM($id);
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
}
