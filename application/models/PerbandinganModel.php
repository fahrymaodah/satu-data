<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class PerbandinganModel extends CI_Model {
	public function getAll()
	{
		$this->db->join('prodi', 'generate.id_prodi = prodi.id_prodi', 'left');
		return $this->db->where('jenis_generate', 'perbandingan')->get('generate')->result_array();
	}

	public function create($formulir)
	{
		$this->db->insert('generate', $formulir);

		$this->db->join('prodi', 'generate.id_prodi = prodi.id_prodi', 'left');
		$generate = $this->db->get_where('generate', array('id_generate' => $this->db->insert_id()))->row_array();

		$tahun = $this->db->where('SUBSTR(nama_tahun, 1, 4) >= '.substr($generate['angkatan_generate'], 0, 4))->order_by('SUBSTR(nama_tahun, 1, 4) ASC')->get('tahun')->result_array();

		$this->db->join('prodi', 'generate.id_prodi = prodi.id_prodi', 'left');
		$this->db->where('generate.id_prodi', $generate['id_prodi'])->where('angkatan_generate', $generate['angkatan_generate'])->where('jenis_generate', 'siska');
		$siska = $this->db->get('generate')->row_array();

		$reader	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); $reader->setReadDataOnly(true); $reader->setReadEmptyCells(false);
		$load	= $reader->load("assets/file/generate/".$siska['file_generate']);
		$read	= $load->getActiveSheet();

		$data_siska = $read->rangeToArray('A1:'.$read->getHighestDataColumn().$read->getHighestDataRow(), null, true, false, false);

		$this->db->where('id_prodi', $generate['id_prodi'])->where('angkatan_generate', $generate['angkatan_generate'])->where('jenis_generate', 'feeder');
		$feeder = $this->db->get('generate')->row_array();

		$reader	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); $reader->setReadDataOnly(true); $reader->setReadEmptyCells(false);
		$load	= $reader->load("assets/file/generate/".$feeder['file_generate']);
		$read	= $load->getActiveSheet();

		$data_feeder = $read->rangeToArray('A1:'.$read->getHighestDataColumn().$read->getHighestDataRow(), null, true, false, false);

		$data_siska		= array_slice($data_siska, 4);
		$data_feeder	= array_slice($data_feeder, 4);

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

		$styleCenter = [
			'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER ],
		];

		$spreadsheet	= new Spreadsheet();
		$sheet			= $spreadsheet->getActiveSheet();

		$startRowHeader	= 2;
		$endRowHeader	= $startRowHeader + 2;

		$sheet->getColumnDimension('A')->setWidth(75, 'px');
		$sheet->getColumnDimension('B')->setWidth(110, 'px');
		$sheet->getColumnDimension('C')->setWidth(355, 'px');

		$sheet->mergeCells('A1:C1'); $sheet->getStyle('A1:C1')->applyFromArray($styleHeader); $sheet->setCellValue('A1', 'Data Histori Perbandingan Nilai dari Siska dan Feeder');

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

		$sheet->fromArray($data_siska, NULL, 'A'.($endRowHeader+1), true);
		$sheet->getStyle('A'.($endRowHeader+1).':'.$endColumnYear.(count($data_siska)+$endRowHeader))->applyFromArray($styleData);
		$sheet->getStyle('D'.($endRowHeader+1).':'.$endColumnYear.(count($data_siska)+$endRowHeader))->applyFromArray($styleCenter);

		$rowSum		= count($data_siska);
		$columnSum	= count($data_siska[0]);

		$cell = []; $nomor = 0;
		for ($i = 0; $i < $rowSum; $i++) { 
			for ($j = 0; $j < $columnSum; $j++) {
				if ($data_siska[$i][$j] == 'N') {
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

		$cell = []; $nomor = 0; $key = '';
		for ($i = 0; $i < $rowSum; $i++) { 
			$key = array_search($data_siska[$i][1], array_column($data_feeder, 1));

			if ($key == '') {
				for ($j = 1; $j < $columnSum; $j++) {
					$col			= getNextExcelColumn('A', $j);
					$row 			= $i + 5;
					$cell[$nomor]	= $col.$row;
					
					$nomor++;
				}
			} else {
				for ($j = 1; $j < $columnSum; $j++) {
					if ($data_siska[$i][$j] != $data_feeder[$key][$j]) {
						$col			= getNextExcelColumn('A', $j);
						$row 			= $i + 5;
						$cell[$nomor]	= $col.$row;

						$nomor++;
					}
				}
			}

			$key = '';
		}

		foreach ($cell as $urut_cell => $per_cell) {
			$sheet->getStyle($per_cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
		}

		$filename = 'Data Histori Perbandingan Nilai dari Siska dan Feeder '.$siska['nama_prodi'].' - '.$siska['angkatan_generate'].'.xlsx';

		$writer = new Xlsx($spreadsheet);
		$writer->save('assets/file/generate/'.$filename);

		$this->db->where('id_generate', $generate['id_generate'])->set('file_generate', $filename)->update('generate');
	}

	public function getDetail($id)
	{
		$this->db->join('prodi', 'generate.id_prodi = prodi.id_prodi', 'left');
		return $this->db->where('id_generate', $id)->get('generate')->row_array();
	}

	public function delete($id)
	{
		$generate = $this->getDetail($id);
		if ($generate['file_generate'] != '') {
			if (file_exists(FCPATH."assets/file/generate/".$generate['file_generate'])) { unlink(FCPATH."assets/file/generate/".$generate['file_generate']); }
		}

		$this->db->where('id_generate', $id)->delete('generate');		
	}

}

/* End of file PerbandinganModel.php */
/* Location: ./application/models/PerbandinganModel.php */