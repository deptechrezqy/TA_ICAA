<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-users-cog"></i> Data Siswa</h1>

</div>

<?= $this->session->flashdata('message'); ?>
<?php if ($this->session->flashdata('siswa_message')): ?>
<div class="alert alert-info">
    <?= $this->session->flashdata('siswa_message'); ?>
</div>
<?php endif; ?>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Daftar Data Siswa</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead class="bg-danger text-white">
                    <tr align="center">
                        <th width="5%">No</th>
                        <th>NISN</th>
                        <th>Nama Siswa</th>
                        <th>Penghasilan Orang Tua</th>
                        <th>Tanggungan Orang Tua</th>
                        <th>Kepemilikan Rumah</th>
                        <th>Nilai Rata-Rata Rapor</th>
                        <th>KIP</th>
                        <th>Verifikasi KIP</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					$no = 1;
					foreach ($list as $data => $siswa) { ?>
                    <tr align="center">
                        <td><?= $no ?></td>
                        <td><?php echo $siswa->nisn ?></td>
                        <td><?php echo $siswa->nama ?></td>
                        <td><?php echo $siswa->penghasilan_ortu ?></td>
                        <td><?php echo $siswa->jumlah_tanggungan ?></td>
                        <td><?php echo $siswa->kepemilikan_rumah ?></td>
                        <td><?php echo $siswa->nilai_rapor ?></td>
                        <td> <?php if (!empty($siswa->file_kip)) { ?>
                            <a href="<?= base_url('/public/uploads/' . $siswa->file_kip); ?>" target="_blank"
                                class="btn btn-info">Lihat File</a>
                            <?php } else { ?>
                            <p class="text-muted">Tidak ada file diupload</p>
                            <?php } ?>
                        </td>
                        <td>
                            <?php if ($siswa->verifikasi_file == 1): ?>
                            <span class="badge badge-success">Terverifikasi</span>
                            <?php elseif ($siswa->verifikasi_file == 2): ?>
                            <span class="badge badge-success">File Tidak Sesuai</span>
                            <?php else: ?>
                            <a href="<?= base_url('siswa/valid/' . $siswa->nisn) ?>" class="btn btn-sm btn-warning"
                                onclick="return confirm('Apakah Anda yakin ingin memverifikasi KIP ini?')">
                                Valid
                            </a>
                            <br>
                            <a href="<?= base_url('siswa/tidakvalid/' . $siswa->nisn) ?>" class="btn btn-sm btn-danger"
                                onclick="return confirm('Apakah Anda yakin ingin memverifikasi KIP ini?')">
                                Tidak Valid
                            </a>
                            <?php endif; ?>
                        </td>

                    </tr>
                    <?php
						$no++;
					}
					?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<?php $this->load->view('layouts/footer_admin'); ?>