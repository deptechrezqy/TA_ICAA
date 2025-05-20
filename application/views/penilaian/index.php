<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-edit"></i> Data Penilaian</h1>
</div>
<?= $this->session->flashdata('message'); ?>
<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Daftar Data Penilaian</h6>

    </div>
    <div class="card-body">

        <?php if ($this->session->flashdata('penilaian_msg')): ?>
        <div class="alert alert-info">
            <?= $this->session->flashdata('penilaian_msg'); ?>
        </div>
        <?php endif; ?>
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
                        <th width="15%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($alternatif as $keys):
                        $siswa = $this->Isi_data_model->get_by_id($keys->siswa_id)
                            ?>
                    <tr align="center">
                        <td><?= $no ?></td>
                        <td><?php echo $siswa->nisn ?></td>
                        <td align="left"><?= $keys->nama ?></td>
                        <td><?php echo $siswa->penghasilan_ortu ?></td>
                        <td><?php echo $siswa->jumlah_tanggungan ?></td>
                        <td><?php echo $siswa->kepemilikan_rumah ?></td>
                        <td><?php echo $siswa->nilai_rapor ?></td>
                        <td>
                            <a href="<?= base_url('Penilaian/recap/' . $keys->id_alternatif) ?>"
                                onclick="return confirm('Apakah Anda yakin ingin memindahkan data ini ke recap?')"
                                class="btn btn-danger btn-sm">
                                Pindah ke Recap
                            </a>
                        </td>
                        <?php
                            $no++;
                    endforeach
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php $this->load->view('layouts/footer_admin'); ?>