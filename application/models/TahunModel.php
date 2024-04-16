<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TahunModel extends CI_Model {
	public function getAll()
	{
		return $this->db->get('tahun')->result_array();
	}

	public function create($formulir)
	{
		$this->db->insert('tahun', $formulir);
	}
	
	public function getDetail($id)
	{
		return $this->db->where('id_tahun', $id)->get('tahun')->row_array();
	}

	public function update($formulir)
	{
		$this->db->where('id_tahun', $formulir['id_tahun'])->update('tahun', $formulir);
	}

	public function delete($id)
	{
		$this->db->where('id_tahun', $id)->delete('tahun');
	}
}

/* End of file TahunModel.php */
/* Location: ./application/models/TahunModel.php */