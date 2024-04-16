<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Feeder extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('admin')) { redirect('', 'refresh'); }

		$this->load->model('TahunModel');
		$this->load->model('ProdiModel');
		$this->load->model('FeederModel');
	}

	public function arsip()
	{
		$data['tahun']	= $this->TahunModel->getAll();
		$data['prodi']	= $this->ProdiModel->getAll();
		$data['feeder'] = $this->FeederModel->getAll();

		$this->load->view('header');
		$this->load->view('arsipFeeder', $data);
		$this->load->view('footer');
	}

	public function tambah()
	{
		$this->form_validation->set_rules('id_tahun', 'Tahun Akademik', 'required');
		$this->form_validation->set_rules('semester_feeder', 'Semester', 'required');
		$this->form_validation->set_rules('id_prodi', 'Program Studi', 'required');

		if (empty($_FILES['file_feeder']['name'])) {
			$this->form_validation->set_rules('file_feeder', 'File Excel Feeder', 'required');
		} else {
			$path = $_FILES['file_feeder']['name'];
			if (pathinfo($path, PATHINFO_EXTENSION) != 'xlsx') {
				$this->form_validation->set_rules('ext_file_feeder', 'ext_file_feeder', 'required', ['required' => 'File extension does not match']);
			}
		}

		if ($this->form_validation->run()) {
			$formulir = $this->input->post();
			$this->FeederModel->create($formulir);

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
		$data = $this->FeederModel->getDetail($this->input->post('id_feeder'));
		echo json_encode($data);
	}

	public function ubah()
	{
		$this->form_validation->set_rules('id_tahun', 'Tahun Akademik', 'required');
		$this->form_validation->set_rules('semester_feeder', 'Semester', 'required');
		$this->form_validation->set_rules('id_prodi', 'Program Studi', 'required');

		if ($this->form_validation->run()) {
			$formulir = $this->input->post();
			$this->FeederModel->update($formulir);

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
		$this->FeederModel->delete($id);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
		redirect('feeder', 'refresh');
	}

	// ===============================================================================================================================================================

	public function generate()
	{
		$data['tahun']		= $this->TahunModel->getAll();
		$data['prodi']		= $this->ProdiModel->getAll();
		$data['generate']	= $this->FeederModel->generateReadAll();

		$this->load->view('header');
		$this->load->view('generateFeeder', $data);
		$this->load->view('footer');
	}

	public function generateTambah()
	{
		$this->form_validation->set_rules('id_prodi', 'Program Studi', 'required');
		$this->form_validation->set_rules('angkatan_generate', 'Angkatan', 'required');

		if ($this->form_validation->run()) {
			$formulir					= $this->input->post();
			$formulir['jenis_generate']	= 'feeder';

			$this->FeederModel->generateCreate($formulir);

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
	// 	$data = $this->FeederModel->generateReadDetail($this->input->post('id_generate'));
	// 	echo json_encode($data);
	// }

	// public function generateUbah()
	// {
	// 	$this->form_validation->set_rules('id_prodi', 'Program Studi', 'required');
	// 	$this->form_validation->set_rules('angkatan_generate', 'Angkatan', 'required');

	// 	if ($this->form_validation->run()) {
	// 		$formulir					= $this->input->post();
	// 		$formulir['jenis_generate']	= 'feeder';

	// 		$this->FeederModel->generateUpdate($formulir);

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
		$this->FeederModel->generateDelete($id);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
		redirect('feeder/generate', 'refresh');
	}

}

/* End of file Feeder.php */
/* Location: ./application/controllers/Feeder.php */