<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class ProdiModel extends CI_Model {
	public function getAll()
	{
		return $this->db->get('prodi')->result_array();
	}

	public function create($formulir)
	{
		$this->db->insert('prodi', $formulir);
	}
	
	public function getDetail($id)
	{
		return $this->db->where('id_prodi', $id)->get('prodi')->row_array();
	}

	public function update($formulir)
	{
		$this->db->where('id_prodi', $formulir['id_prodi'])->update('prodi', $formulir);
	}

	public function delete($id)
	{
		$this->db->where('id_prodi', $id)->delete('prodi');
	}
}

/* End of file ProdiModel.php */
/* Location: ./application/models/ProdiModel.php */