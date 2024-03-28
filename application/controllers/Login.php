<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model("OracleDBM");
		$this->load->model("AdminM");
		$this->load->model("TrainingM");
		$this->load->library('session');
	}

	public function index()
	{
		$this->load->view('index');
	}

	public function checkLogin()
	{
		$post = $this->input->post();
		if (isset($post['npk']) && isset($post['password'])) {
			$data = $this->OracleDBM->getEmpByNPK($post['npk']);
			if ($data != null) {
				$npk = $data->NPK;
				$nama = $data->NAMA;
				$departemen = $data->DEPARTEMEN;
				$newdata = array(
					'npk' => $npk,
					'nama' => $nama,
					'isLogin' => true,
					'departemen' => $departemen,
				);
				$newdata['role'] = $this->AdminM->isNpkAdmin($npk) ? 'admin' : 'nonAdmin';
				$this->session->set_userdata($newdata);
				redirect('Training');
			} else {
				$this->session->set_flashdata('error_message', 'NPK atau Password yang dimasukkan tidak sesuai!');

				redirect(site_url());
			}
		}
	}

	public function logout()
	{
		$this->session->unset_userdata('isLogin');
		$this->session->sess_destroy();
		redirect(site_url());
	}
}
