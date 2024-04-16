<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perbandingan extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('admin')) { redirect('', 'refresh'); }

		$this->load->model('TahunModel');
		$this->load->model('ProdiModel');
		$this->load->model('PerbandinganModel');
	}

	public function index()
	{
		$data['tahun']		= $this->TahunModel->getAll();
		$data['prodi']		= $this->ProdiModel->getAll();
		$data['generate']	= $this->PerbandinganModel->getAll();

		$this->load->view('header');
		$this->load->view('generatePerbandingan', $data);
		$this->load->view('footer');
	}

	public function tambah()
	{
		$this->form_validation->set_rules('id_prodi', 'Program Studi', 'required');
		$this->form_validation->set_rules('angkatan_generate', 'Angkatan', 'required');

		if ($this->form_validation->run()) {
			$formulir					= $this->input->post();
			$formulir['jenis_generate']	= 'perbandingan';

			$this->PerbandinganModel->create($formulir);

			$this->session->set_flashdata('pesan', 'Data berhasil ditambahkan!');
			$pesan = [ 'status' => 'sukses' ];
		}
		else {
			$validasi = $this->form_validation->error_array();
			$pesan = [ 'status' => 'gagal', 'validasi' => $this->form_validation->error_array() ];
		}

		echo json_encode($pesan);
	}

	public function hapus($id)
	{
		$this->PerbandinganModel->delete($id);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
		redirect('perbandingan', 'refresh');
	}

}

/* End of file Perbandingan.php */
/* Location: ./application/controllers/Perbandingan.php */