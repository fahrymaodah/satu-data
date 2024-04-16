<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Siska extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('admin')) { redirect('', 'refresh'); }

		$this->load->model('TahunModel');
		$this->load->model('ProdiModel');
		$this->load->model('SiskaModel');
	}

	public function arsip()
	{
		$data['tahun'] = $this->TahunModel->getAll();
		$data['prodi'] = $this->ProdiModel->getAll();
		$data['siska'] = $this->SiskaModel->getAll();

		$this->load->view('header');
		$this->load->view('arsipSiska', $data);
		$this->load->view('footer');
	}

	public function tambah()
	{
		$this->form_validation->set_rules('id_tahun', 'Tahun Akademik', 'required');
		$this->form_validation->set_rules('semester_siska', 'Semester', 'required');
		$this->form_validation->set_rules('id_prodi', 'Program Studi', 'required');
		$this->form_validation->set_rules('angkatan_siska', 'Angkatan', 'required');

		if (empty($_FILES['file_siska']['name'])) {
			$this->form_validation->set_rules('file_siska', 'File Excel Siska', 'required');
		} else {
			$path = $_FILES['file_siska']['name'];
			if (pathinfo($path, PATHINFO_EXTENSION) != 'xlsx') {
				$this->form_validation->set_rules('ext_file_siska', 'ext_file_siska', 'required', ['required' => 'File extension does not match']);
			}
		}

		if ($this->form_validation->run()) {
			$formulir = $this->input->post();
			$this->SiskaModel->create($formulir);

			$this->session->set_flashdata('pesan', 'Data berhasil ditambahkan!');
			$pesan = [ 'status' => 'sukses' ];
		}
		else {
			$validasi = $this->form_validation->error_array();
			$pesan = [ 'status' => 'gagal', 'validasi' => $this->form_validation->error_array() ];
		}

		echo json_encode($pesan);
	}

	public function detail()
	{
		$data = $this->SiskaModel->getDetail($this->input->post('id_siska'));
		echo json_encode($data);
	}

	public function ubah()
	{
		$this->form_validation->set_rules('id_tahun', 'Tahun Akademik', 'required');
		$this->form_validation->set_rules('semester_siska', 'Semester', 'required');
		$this->form_validation->set_rules('id_prodi', 'Program Studi', 'required');
		$this->form_validation->set_rules('angkatan_siska', 'Angkatan', 'required');

		if ($this->form_validation->run()) {
			$formulir = $this->input->post();
			$this->SiskaModel->update($formulir);

			$this->session->set_flashdata('pesan', 'Data berhasil diubah!');
			$pesan = [ 'status' => 'sukses' ];
		}
		else {
			$validasi = $this->form_validation->error_array();
			$pesan = [ 'status' => 'gagal', 'validasi' => $validasi ];
		}

		echo json_encode($pesan);
	}

	public function hapus($id)
	{
		$this->SiskaModel->delete($id);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
		redirect('siska/arsip', 'refresh');
	}

	// ===============================================================================================================================================================

	public function generate()
	{
		$data['tahun']		= $this->TahunModel->getAll();
		$data['prodi']		= $this->ProdiModel->getAll();
		$data['generate']	= $this->SiskaModel->generateReadAll();

		$this->load->view('header');
		$this->load->view('generateSiska', $data);
		$this->load->view('footer');
	}

	public function generateTambah()
	{
		$this->form_validation->set_rules('id_prodi', 'Program Studi', 'required');
		$this->form_validation->set_rules('angkatan_generate', 'Angkatan', 'required');

		if ($this->form_validation->run()) {
			$formulir					= $this->input->post();
			$formulir['jenis_generate']	= 'siska';

			$this->SiskaModel->generateCreate($formulir);

			$this->session->set_flashdata('pesan', 'Data berhasil ditambahkan!');
			$pesan = [ 'status' => 'sukses' ];
		}
		else {
			$validasi = $this->form_validation->error_array();
			$pesan = [ 'status' => 'gagal', 'validasi' => $this->form_validation->error_array() ];
		}

		echo json_encode($pesan);
	}

	// public function generateDetail()
	// {
	// 	$data = $this->SiskaModel->generateReadDetail($this->input->post('id_generate'));
	// 	echo json_encode($data);
	// }

	// public function generateUbah()
	// {
	// 	$this->form_validation->set_rules('id_prodi', 'Program Studi', 'required');
	// 	$this->form_validation->set_rules('angkatan_generate', 'Angkatan', 'required');

	// 	if ($this->form_validation->run()) {
	// 		$formulir					= $this->input->post();
	// 		$formulir['jenis_generate']	= 'siska';

	// 		$this->SiskaModel->generateUpdate($formulir);

	// 		$this->session->set_flashdata('pesan', 'Data berhasil ditambahkan!');
	// 		$pesan = [ 'status' => 'sukses' ];
	// 	}
	// 	else {
	// 		$validasi = $this->form_validation->error_array();
	// 		$pesan = [ 'status' => 'gagal', 'validasi' => $this->form_validation->error_array() ];
	// 	}

	// 	echo json_encode($pesan);
	// }

	public function generateHapus($id)
	{
		$this->SiskaModel->generateDelete($id);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
		redirect('siska/generate', 'refresh');
	}

}

/* End of file Siska.php */
/* Location: ./application/controllers/Siska.php */