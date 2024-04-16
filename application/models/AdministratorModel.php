<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class AdministratorModel extends CI_Model {
	function login($formulir)
	{
		$email		= $formulir['email'];
		$password	= sha1($formulir['password']);

		$data = $this->db->where('email_administrator', $email)->where('password_administrator', $password)->get('administrator')->row_array();

		if (empty($data)) {
			$data['status'] = 'gagal';
		}
		else {
			$this->session->set_userdata('admin', $data);
			$data['status'] = 'sukses';
		}

		return $data;
	}
}

/* End of file AdministratorModel.php */
/* Location: ./application/models/AdministratorModel.php */