<?php

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

defined('BASEPATH') or exit('No direct script access allowed');

class Recap extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->library('pagination');
        $this->load->library('form_validation');
        $this->load->model('Isi_data_model');
        $this->load->model('Alternatif_model');
        $this->load->model('Penilaian_model');

        if ($this->session->userdata('id_user_level') != "1") {
            ?>
<script type="text/javascript">
alert('Anda tidak berhak mengakses halaman ini!');
window.location = '<?php echo base_url("Login/home"); ?>'
</script>
<?php
        }
    }

    public function index()
    {
        $data = [
            'page' => "Recap",
            'list' => $this->Isi_data_model->recap_all(),
        ];
        $this->load->view('recap/index', $data);
    }

    public function export_excel()
    {

        $list = $this->Isi_data_model->recap_all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set Header
        $sheet->setCellValue('A1', 'No');
        $sheet->setCellValue('B1', 'NISN');
        $sheet->setCellValue('C1', 'Alternatif');
        $sheet->setCellValue('D1', 'Penghasilan Orang Tua');
        $sheet->setCellValue('E1', 'Tanggungan Orang Tua');
        $sheet->setCellValue('F1', 'Kepemilikan Rumah');
        $sheet->setCellValue('G1', 'Nilai Rata-Rata Rapor');
        $sheet->setCellValue('H1', 'Nilai Rata-Rata Rapor');
        $sheet->setCellValue('I1', 'Tanggal');

        // Isi Data
        $row = 2;
        $no = 1;
        foreach ($list as $siswa) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $siswa->nisn);
            $sheet->setCellValue('C' . $row, $siswa->nama);
            $sheet->setCellValue('D' . $row, $siswa->penghasilan_ortu);
            $sheet->setCellValue('E' . $row, $siswa->jumlah_tanggungan);
            $sheet->setCellValue('F' . $row, $siswa->kepemilikan_rumah);
            $sheet->setCellValue('G' . $row, $siswa->nilai_rapor);
            $sheet->setCellValue('H' . $row, $siswa->nilai_hasil);
            $sheet->setCellValue('I' . $row, date('d-m-Y', strtotime($siswa->created_at)));
            $row++;
        }

        // Download
        $filename = 'Data_Recap_Siswa.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

}