<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tahun extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('admin')) { redirect('', 'refresh'); }

		$this->load->model('TahunModel');
	}

	public function index()
	{
		$data['tahun'] = $this->TahunModel->getAll();

		$this->load->view('header');
		$this->load->view('tahun', $data);
		$this->load->view('footer');
	}

	public function tambah()
	{
		$this->form_validation->set_rules('nama_tahun', 'Tahun Akademik', 'required|is_unique[tahun.nama_tahun]');

		if ($this->form_validation->run()) {
			$formulir = $this->input->post();
			$this->TahunModel->create($formulir);

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
		$data = $this->TahunModel->getDetail($this->input->post('id_tahun'));
		echo json_encode($data);
	}

	public function ubah()
	{
		$formulir	= $this->input->post();
		$data		= $this->TahunModel->getDetail($formulir['id_tahun']);

		$namaUnik = $formulir['nama_tahun'] == $data['nama_tahun'] ? '' : '|is_unique[tahun.nama_tahun]';

		$this->form_validation->set_rules('nama_tahun', 'Tahun Akademik', 'required'.$namaUnik);

		if ($this->form_validation->run()) {
			$this->TahunModel->update($formulir);

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
		$this->TahunModel->delete($id);

		$this->session->set_flashdata('pesan', 'Data berhasil dihapus!');
		redirect('tahun', 'refresh');
	}

}

/* End of file Tahun.php */
/* Location: ./application/controllers/Tahun.php */