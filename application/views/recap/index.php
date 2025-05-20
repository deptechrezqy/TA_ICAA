<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-edit"></i> Data Recap Siswa</h1>
</div>
<?= $this->session->flashdata('message'); ?>
<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3 d-flex justify-content-between align-items-center">
        <h6 class="m-0 font-weight-bold text-danger">
            <i class="fa fa-table"></i> Daftar Data Recap Siswa
        </h6>
        <a href="<?= base_url('recap/export_excel') ?>" class="btn btn-success">
            <i class="fa fa-file-excel"></i> Export Excel
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-danger text-white">
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>NISN</th>
                        <th>Alternatif</th>
                        <th>Penghasilan Orang Tua</th>
                        <th>Tanggungan Orang Tua</th>
                        <th>Kepemilikan Rumah</th>
                        <th>Nilai Rata-Rata Rapor</th>
                        <th>Nilai</th>
                        <th>Tanggal</th>

                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    $ranking = 1;


                    foreach ($list as $siswa):

                        ?>
                    <tr align="center">
                        <td><?= $no++; ?></td>
                        <td><?= htmlspecialchars($siswa->nisn) ?></td>
                        <td align="left"><?= htmlspecialchars($siswa->nama) ?></td>
                        <td><?= htmlspecialchars($siswa->penghasilan_ortu) ?></td>
                        <td><?= htmlspecialchars($siswa->jumlah_tanggungan) ?></td>
                        <td><?= htmlspecialchars($siswa->kepemilikan_rumah) ?></td>
                        <td><?= htmlspecialchars($siswa->nilai_rapor) ?></td>
                        <td><?= is_numeric($siswa->nilai_hasil) ? number_format($siswa->nilai_hasil, 4) : '-' ?></td>
                        <td><?= date('d-M-Y', strtotime($siswa->created_at)) ?></td>
                    </tr>
                    <?php endforeach; ?>

                </tbody>
            </table>
        </div>
    </div>

    <?php $this->load->view('layouts/footer_admin'); ?>