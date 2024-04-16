<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Prodi extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('admin')) { redirect('', 'refresh'); }

		$this->load->model('ProdiModel');
	}

	public function index()
	{
		$data['prodi'] = $this->ProdiModel->getAll();

		$this->load->view('header');
		$this->load->view('prodi', $data);
		$this->load->view('footer');
	}

	public function tambah()
	{
		$this->form_validation->set_rules('nama_prodi', 'Program Studi', 'required|is_unique[prodi.nama_prodi]');

		if ($this->form_validation->run()) {
			$formulir = $this->input->post();
			$this->ProdiModel->create($formulir);

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
		$data = $this->ProdiModel->getDetail($this->input->post('id_prodi'));
		echo json_encode($data);
	}

	public function ubah()
	{
		$formulir	= $this->input->post();
		$data		= $this->ProdiModel->getDetail($formulir['id_prodi']);

		$namaUnik = $formulir['nama_prodi'] == $data['nama_prodi'] ? '' : '|is_unique[prodi.nama_prodi]';

		$this->form_validation->set_rules('nama_prodi', 'Program Studi', 'required'.$namaUnik);

		if ($this->form_validation->run()) {
			$this->ProdiModel->update($formulir);

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
		$this->ProdiModel->delete($id);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
		redirect('prodi', 'refresh');
	}

}

/* End of file Prodi.php */
/* Location: ./application/controllers/Prodi.php */