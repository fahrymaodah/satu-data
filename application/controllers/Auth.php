<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
	public function index()
	{
		$this->load->model('AdministratorModel');

		if ($this->session->userdata('admin'))
			redirect('dashboard', 'refresh');

		$this->form_validation->set_rules('email', 'Email', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == FALSE) {
        	$data['error_message'] = $this->form_validation->error('field_name');
        }
        else {
        	$formulir = $this->input->post();
			if ($formulir) {
				$admin = $this->AdministratorModel->login($formulir);
				if ($admin['status'] == 'gagal') {
					redirect('', 'refresh');
				}

				$admin = $this->session->set_userdata('admin', $admin);
				redirect('dashboard', 'refresh');
			}
        }

		$this->load->view('login');
	}

	function logout()
	{
		$this->session->sess_destroy();
		redirect('', 'refresh');
	}

}

/* End of file Auth.php */
/* Location: ./application/controllers/Auth.php */