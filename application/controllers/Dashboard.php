<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Style\Conditional;

class Dashboard extends CI_Controller {
	public function __construct()
	{
		parent::__construct();
		if (!$this->session->userdata('admin')) { redirect('', 'refresh'); }
	}

	public function index()
	{
		$this->load->view('header');
		// $this->load->view('dashboard');
		$this->load->view('footer');
	}

	public function file()
	{
		$this->db->join('prodi', 'generate.id_prodi = prodi.id_prodi', 'left');
		$generate = $this->db->where('id_generate', 1)->get('generate')->row_array();
		
		$tahun = $this->db->where('SUBSTR(nama_tahun, 1, 4) >= '.substr($generate['angkatan_generate'], 0, 4))->order_by('SUBSTR(nama_tahun, 1, 4) ASC')->get('tahun')->result_array();

		$this->db->join('tahun', 'feeder.id_tahun = tahun.id_tahun', 'left');
		$feeder = $this->db->where_in('feeder.id_tahun', array_column($tahun, 'id_tahun'))->order_by('SUBSTR(nama_tahun, 1, 4) ASC, semester_feeder ASC')->get('feeder')->result_array();

		foreach ($feeder as $urut_feeder => $per_feeder) {
			$reader	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); $reader->setReadDataOnly(true); $reader->setReadEmptyCells(false);
			$load	= $reader->load("assets/file/feeder/".$per_feeder['file_feeder']);
			$read	= $load->getActiveSheet();

			$data = $read->rangeToArray('A1:'.$read->getHighestDataColumn().$read->getHighestDataRow(), null, true, false, false);

			// Mendapatkan jumlah baris dan kolom di worksheet
			// $highestRow		= $data_feeder->getHighestDataRow(); // Mendapatkan baris teratas yang memiliki data
			// $highestColumn	= $data_feeder->getHighestDataColumn(); // Mendapatkan kolom terkanan yang memiliki data

			// foreach ($data_feeder as $urut_data_feeder => $per_data_feeder) {
			// 	echo "<pre>";
			// 	print_r ($per_data_feeder);
			// 	echo "</pre>";
			// }

			echo "<pre>";
			print_r ($data);
			echo "</pre>";

			exit();

		}
	}

	public function export()
	{
		$this->db->join('prodi', 'generate.id_prodi = prodi.id_prodi', 'left');
		$generate = $this->db->where('id_generate', 1)->get('generate')->row_array();
		
		$tahun = $this->db->where('SUBSTR(nama_tahun, 1, 4) >= '.substr($generate['angkatan_generate'], 0, 4))->order_by('SUBSTR(nama_tahun, 1, 4) ASC')->get('tahun')->result_array();

		$this->db->join('tahun', 'feeder.id_tahun = tahun.id_tahun', 'left');
		$feeder = $this->db->where_in('feeder.id_tahun', array_column($tahun, 'id_tahun'))->order_by('SUBSTR(nama_tahun, 1, 4) ASC, semester_feeder ASC')->get('feeder')->result_array();

		$styleHeader = [
			'font' => [ 'bold' => true, 'size' => 12 ],
			'alignment' => [ 'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER, 'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER ],
			'borders' => [ 'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOUBLE] ],
			// 'fill' => [ 'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'startColor' => [ 'argb' => 'FFB1A0C7' ] ],
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
		$headerPotongan	= [];
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
						$mahasiswa[$urut_mahasiswa][$kolom++] =  $urut_feeder == 0 ? '' : $per_feeder[$key][6];
						$mahasiswa[$urut_mahasiswa][$kolom++] =  $urut_feeder == 0 ? '' : $per_feeder[$key][5];
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

		// $conditional = new Conditional();
		// $conditional->setConditionType(Conditional::CONDITION_CELLIS);
		// $conditional->setOperatorType(Conditional::OPERATOR_EQUAL);
		// $conditional->addCondition('N');
		// $conditional->getStyle()->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		// $conditional->getStyle()->getFill()->getEndColor()->setARGB(\PhpOffice\PhpSpreadsheet\Style\Color::COLOR_YELLOW);

		// $conditionalStyles		= $sheet->getStyle('D'.($endRowHeader+1).':'.$endColumnYear.(count($mahasiswa)+$endRowHeader))->getConditionalStyles();
		// $conditionalStyles[]	= $conditional;

		// $sheet->getStyle('D'.($endRowHeader+1).':'.$endColumnYear.(count($mahasiswa)+$endRowHeader))->setConditionalStyles([$conditionalStyles]);

		$filename = 'Data Histori Feeder '.$generate['nama_prodi'].' - '.$generate['angkatan_generate'].'.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit();
	}

	public function compare()
	{
		$id_prodi = 1;
		$angkatan_compare = '2019-2020';

		$tahun = $this->db->where('SUBSTR(nama_tahun, 1, 4) >= '.substr($angkatan_compare, 0, 4))->order_by('SUBSTR(nama_tahun, 1, 4) ASC')->get('tahun')->result_array();

		$this->db->join('prodi', 'generate.id_prodi = prodi.id_prodi', 'left');
		$this->db->where('generate.id_prodi', $id_prodi)->where('angkatan_generate', $angkatan_compare)->where('jenis_generate', 'siska');
		$siska = $this->db->get('generate')->row_array();

		$reader	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); $reader->setReadDataOnly(true); $reader->setReadEmptyCells(false);
		$load	= $reader->load("assets/file/generate/".$siska['file_generate']);
		$read	= $load->getActiveSheet();

		$data_siska = $read->rangeToArray('A1:'.$read->getHighestDataColumn().$read->getHighestDataRow(), null, true, false, false);

		$this->db->where('id_prodi', $id_prodi)->where('angkatan_generate', $angkatan_compare)->where('jenis_generate', 'feeder');
		$feeder = $this->db->get('generate')->row_array();

		$reader	= new \PhpOffice\PhpSpreadsheet\Reader\Xlsx(); $reader->setReadDataOnly(true); $reader->setReadEmptyCells(false);
		$load	= $reader->load("assets/file/generate/".$feeder['file_generate']);
		$read	= $load->getActiveSheet();

		$data_feeder = $read->rangeToArray('A1:'.$read->getHighestDataColumn().$read->getHighestDataRow(), null, true, false, false);

		$data_siska		= array_slice($data_siska, 4);
		$data_feeder	= array_slice($data_feeder, 4);

		$rowSum		= count($data_siska);
		$columnSum	= count($data_siska[0]);

		$cell = []; $nomor = 0;
		for ($i = 0; $i < $rowSum; $i++) { 
			for ($j = 0; $j < $columnSum; $j++) {
				if ($data_siska[$i][$j] != $data_feeder[$i][$j]) {
					$col			= getNextExcelColumn('A', $j);
					$row 			= $i + 5;
					$cell[$nomor]	= $col.$row;

					$nomor++;
				}
			}
		}

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

		foreach ($cell as $urut_cell => $per_cell) {
			$sheet->getStyle($per_cell)->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)->getStartColor()->setARGB('FFFF0000');
		}

		$filename = 'Data Histori Perbandingan Nilai dari Siska dan Feeder '.$siska['nama_prodi'].' - '.$siska['angkatan_generate'].'.xlsx';

		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename='.$filename);
		header('Cache-Control: max-age=0');

		$writer = new Xlsx($spreadsheet);
		$writer->save('php://output');
		exit();
	}

}

/* End of file Dashboard.php */
/* Location: ./application/controllers/Dashboard.php */ ?>