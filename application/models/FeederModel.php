<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class FeederModel extends CI_Model {
	public function getAll()
	{
		$this->db->join('tahun', 'feeder.id_tahun = tahun.id_tahun', 'left');
		$this->db->join('prodi', 'feeder.id_prodi = prodi.id_prodi', 'left');
		return $this->db->get('feeder')->result_array();
	}

	public function create($formulir)
	{
		$config['upload_path']		= FCPATH.'/assets/file/feeder/';
		$config['allowed_types']	= 'xlsx';
		$config['max_size'] 		= '2048';
		$config['file_name']		= date('YmdHis').'_'.$_FILES['file_feeder']['name'];

		$this->upload->initialize($config);
		$file = $this->upload->do_upload('file_feeder');

		if ($file) { $formulir['file_feeder'] = $this->upload->data('file_name'); }

		$this->db->insert('feeder', $formulir);
	}
	
	public function getDetail($id)
	{
		$this->db->join('tahun', 'feeder.id_tahun = tahun.id_tahun', 'left');
		$this->db->join('prodi', 'feeder.id_prodi = prodi.id_prodi', 'left');
		return $this->db->where('id_feeder', $id)->get('feeder')->row_array();
	}

	public function update($formulir)
	{
		$config['upload_path']		= FCPATH.'/assets/file/feeder/';
		$config['allowed_types']	= 'xlsx';
		$config['max_size'] 		= '2048';
		$config['file_name']		= date('YmdHis').'_'.$_FILES['file_feeder']['name'];

		$this->upload->initialize($config);
		$file = $this->upload->do_upload('file_feeder');

		if ($file)
		{
			$feeder = $this->detail_feeder($id_feeder);
			if ($feeder['file_feeder'] != '')
			{
				if (file_exists(FCPATH."assets/file/feeder/".$feeder['file_feeder'])) { unlink(FCPATH."assets/file/feeder/".$feeder['file_feeder']); }
			}

			$formulir['file_feeder'] = $this->upload->data('file_name');
		}

		$this->db->where('id_feeder', $formulir['id_feeder'])->update('feeder', $formulir);
	}

	public function delete($id)
	{
		$feeder = $this->detail_feeder($id_feeder);
		if ($feeder['file_feeder'] != '')
		{
			if (file_exists(FCPATH."assets/file/feeder/".$feeder['file_feeder'])) { unlink(FCPATH."assets/file/feeder/".$feeder['file_feeder']); }
		}

		$this->db->where('id_feeder', $id)->delete('feeder');
	}

	public function generateReadAll()
	{
		$this->db->join('prodi', 'generate.id_prodi = prodi.id_prodi', 'left');
		return $this->db->where('jenis_generate', 'feeder')->get('generate')->result_array();
	}

	public function generateCreate($formulir)
	{
		$this->db->insert('generate', $formulir);

		$this->db->join('prodi', 'generate.id_prodi = prodi.id_prodi', 'left');
		$generate = $this->db->get_where('generate', array('id_generate' => $this->db->insert_id()))->row_array();
		
		$tahun = $this->db->where('SUBSTR(nama_tahun, 1, 4) >= '.substr($generate['angkatan_generate'], 0, 4))->order_by('SUBSTR(nama_tahun, 1, 4) ASC')->get('tahun')->result_array();

		$this->db->join('tahun', 'feeder.id_tahun = tahun.id_tahun', 'left');
		$feeder = $this->db->where('id_prodi', $generate['id_prodi'])->where_in('feeder.id_tahun', array_column($tahun, 'id_tahun'))->order_by('SUBSTR(nama_tahun, 1, 4) ASC, semester_feeder ASC')->get('feeder')->result_array();

		$styleHeader = [
			'font' => [ 'bold' => true, 'size' => 12 ],
			'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER ],
			'borders' => [ 'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE] ],
		];

		$styleData = [
			'font' => [ 'size' => 11 ],
			'alignment' => [ 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER ],
			'borders' => [ 'outline' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE], 'inside' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN] ],
		];

		$spreadsheet	= new Spreadsheet();
		$sheet			= $spreadsheet->getActiveSheet();

		$sheet->mergeCells('A1:C1'); $sheet->setCellValue('A1', 'Data Histori Nilai dari Feeder');
		$sheet->getStyle('A1:C1')->applyFromArray($styleHeader);

		$startRowHeader	= 2;
		$endRowHeader	= $startRowHeader + 2;

		$sheet->getColumnDimension('A')->setWidth(75, 'px');
		$sheet->getColumnDimension('B')->setWidth(110, 'px');
		$sheet->getColumnDimension('C')->setWidth(355, 'px');

		$sheet->mergeCells('A'.$startRowHeader.':A'.$endRowHeader); $sheet->setCellValue('A'.$startRowHeader, 'No.');
		$sheet->mergeCells('B'.$startRowHeader.':B'.$endRowHeader); $sheet->setCellValue('B'.$startRowHeader, 'NIM');
		$sheet->mergeCells('C'.$startRowHeader.':C'.$endRowHeader); $sheet->setCellValue('C'.$startRowHeader, 'Nama');

		$startColumnYear	= 'D';
		$endColumnYear		= getNextExcelColumn($startColumnYear, 9);
		foreach ($tahun as $urut_tahun => $per_tahun) {
			$sheet->mergeCells($startColumnYear.$startRowHeader.':'.$endColumnYear.$startRowHeader);
			$sheet->setCellValue($startColumnYear.$startRowHeader, $per_tahun['nama_tahun']);

			$startColumnSemester	= $startColumnYear;
			$endColumnSemester		= getNextExcelColumn($startColumnSemester, 4);
			for ($i = 0; $i < 2; $i++) {
				$semester = $i == 0 ? 'Ganjil' : 'Genap';

				$sheet->mergeCells($startColumnSemester.($startRowHeader+1).':'.$endColumnSemester.($startRowHeader+1));
				$sheet->setCellValue($startColumnSemester.($startRowHeader+1), $semester);

				$content = ['ADM', 'SKS', 'IPS', 'IPK', 'SKSN'];
				foreach ($content as $urut_content => $per_content) {
					$columnContent = getNextExcelColumn($startColumnSemester, $urut_content);
					$sheet->setCellValue($columnContent.($startRowHeader+2), $per_content);
				}

				if ($i == 0) {
					$startColumnSemester	= getNextExcelColumn($startColumnSemester, 5);
					$endColumnSemester		= getNextExcelColumn($endColumnSemester, 5);
				}
			}

			if ($urut_tahun != count($tahun)-1) {
				$startColumnYear	= getNextExcelColumn($startColumnYear, 10);
				$endColumnYear		= getNextExcelColumn($endColumnYear, 10);
			}
		}

		$sheet->getStyle('A'.$startRowHeader.':'.$endColumnYear.$endRowHeader)->applyFromArray($styleHeader);
		$sheet->freezePane('D'.($endRowHeader+1));

		$bank_feeder = []; $bank_mahasiswa = []; $nomor = 0;
		foreach ($feeder as $urut_feeder => $per_feeder) {
			$reader	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); $reader->setReadDataOnly(true); $reader->setReadEmptyCells(false);
			$load	= $reader->load("assets/file/feeder/".$per_feeder['file_feeder']);
			$read	= $load->getActiveSheet();

			$data_feeder = $read->rangeToArray('A1:'.$read->getHighestDataColumn().$read->getHighestDataRow(), null, true, false, false);
			$data_feeder = array_slice($data_feeder, 5);

			foreach ($data_feeder as $urut_data_feeder => $per_data_feeder) {
				if (substr($generate['angkatan_generate'], 2, 2) == substr($per_data_feeder[1], 0, 2)) {
					if (!in_array($per_data_feeder[1], array_column($bank_mahasiswa, 1))) {
						$bank_mahasiswa[$nomor][1] = $per_data_feeder[1];
						$bank_mahasiswa[$nomor][2] = $per_data_feeder[2];

						$nomor++;
					}
					else {
						$key = array_search($per_data_feeder[1], array_column($bank_mahasiswa, 1));

						$bank_mahasiswa[$key][1] = $per_data_feeder[1];
						$bank_mahasiswa[$key][2] = $per_data_feeder[2];
					}
				}
			}

			$bank_feeder[$urut_feeder] = $data_feeder;
		}

		array_multisort(array_column($bank_mahasiswa, 1), SORT_ASC, $bank_mahasiswa);

		$mahasiswa = [];
		foreach ($bank_mahasiswa as $urut_mahasiswa => $per_mahasiswa) {
			$mahasiswa[$urut_mahasiswa][0] = $urut_mahasiswa + 1;
			$mahasiswa[$urut_mahasiswa][1] = $per_mahasiswa[1];
			$mahasiswa[$urut_mahasiswa][2] = $per_mahasiswa[2];

			$kolom = 3; $sksn = 0;
			foreach ($bank_feeder as $urut_feeder => $per_feeder) {
				$key = array_search($per_mahasiswa[1], array_column($per_feeder, 1));

				if ($key !== false) {
					if ($per_feeder[$key][4] == 'Aktif') {
						$sksn += $per_feeder[$key][6];

						$mahasiswa[$urut_mahasiswa][$kolom++] = 'A';
						$mahasiswa[$urut_mahasiswa][$kolom++] = $urut_feeder == 0 ? '' : $per_feeder[$key][6];
						$mahasiswa[$urut_mahasiswa][$kolom++] = $urut_feeder == 0 ? '' : $per_feeder[$key][5];
						$mahasiswa[$urut_mahasiswa][$kolom++] = $per_feeder[$key][7];
						$mahasiswa[$urut_mahasiswa][$kolom++] = $sksn;
					}
					else {
						$mahasiswa[$urut_mahasiswa][$kolom++] = 'N';
						$mahasiswa[$urut_mahasiswa][$kolom++] = '';
						$mahasiswa[$urut_mahasiswa][$kolom++] = '';
						$mahasiswa[$urut_mahasiswa][$kolom++] = '';
						$mahasiswa[$urut_mahasiswa][$kolom++] = '';
					}
				}
				else {
					$mahasiswa[$urut_mahasiswa][$kolom++] = 'N';
					$mahasiswa[$urut_mahasiswa][$kolom++] = '';
					$mahasiswa[$urut_mahasiswa][$kolom++] = '';
					$mahasiswa[$urut_mahasiswa][$kolom++] = '';
					$mahasiswa[$urut_mahasiswa][$kolom++] = '';
				}
			}
		}

		$styleCenter = [
			'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER ],
		];

		$sheet->fromArray($mahasiswa, NULL, 'A'.($endRowHeader+1), true);
		$sheet->getStyle('A'.($endRowHeader+1).':'.$endColumnYear.(count($mahasiswa)+$endRowHeader))->applyFromArray($styleData);
		$sheet->getStyle('D'.($endRowHeader+1).':'.$endColumnYear.(count($mahasiswa)+$endRowHeader))->applyFromArray($styleCenter);

		$rowSum		= count($mahasiswa);
		$columnSum	= count($mahasiswa[0]);

		$cell = []; $nomor = 0;
		for ($i = 0; $i < $rowSum; $i++) { 
			for ($j = 0; $j < $columnSum; $j++) {
				if ($mahasiswa[$i][$j] == 'N') {
					$colStart	= getNextExcelColumn('A', $j);
					$rowStart	= $i + 5;

					$colEnd	= getNextExcelColumn($colStart, 4);
					$rowEnd	= $i + 5;

					$cell[$nomor]	= $colStart.$rowStart.':'.$colEnd.$rowEnd;

					$nomor++;
				}
			}
		}

		foreach ($cell as $urut_cell => $per_cell) {
			$sheet->getStyle($per_cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFFFF00');
		}

		$filename = 'Data Histori Feeder '.$generate['nama_prodi'].' - '.$generate['angkatan_generate'].'.xlsx';

		$writer = new Xlsx($spreadsheet);
		$writer->save('assets/file/generate/'.$filename);

		$this->db->where('id_generate', $generate['id_generate'])->set('file_generate', $filename)->update('generate');
	}

	public function generateReadDetail($id)
	{
		$this->db->join('prodi', 'generate.id_prodi = prodi.id_prodi', 'left');
		return $this->db->where('id_generate', $id)->get('generate')->row_array();
	}

	// public function generateUpdate($formulir)
	// {
	// 	$this->db->where('id_generate', $formulir['id_generate'])->update('generate', $formulir);
	// }

	public function generateDelete($id)
	{
		$generate = $this->generateReadDetail($id_generate);
		if ($generate['file_generate'] != '') {
			if (file_exists(FCPATH."assets/file/generate/".$generate['file_generate'])) { unlink(FCPATH."assets/file/generate/".$generate['file_generate']); }
		}

		$this->db->where('id_generate', $id)->delete('generate');		
	}
}

/* End of file FeederModel.php */
/* Location: ./application/models/FeederModel.php */