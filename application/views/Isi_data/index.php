<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cube"></i> Data Siswa</h1>
</div>

<?= $this->session->flashdata('message'); ?>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Data Siswa</h6>
    </div>

    <?php
    // Ambil data siswa berdasarkan user yang sedang login
    $user_id = $this->session->userdata('id_user');
    $siswa = $this->Isi_data_model->get_siswa_by_user($user_id);
    ?>

    <div class="card-body">
        <div class="row">
            <div class="form-group col-md-4">
                <label class="font-weight-bold">NISN</label>
                <input type="text" name="nisn" class="form-control" value="<?= isset($siswa) ? $siswa->nisn : ''; ?>" readonly />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Nama Siswa</label>
                <input type="text" name="nama" class="form-control" value="<?= isset($siswa) ? $siswa->nama : ''; ?>" readonly />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Penghasilan Orang Tua</label>
                <input type="text" name="penghasilan_ortu" class="form-control" value="<?= isset($siswa) ? $siswa->penghasilan_ortu : ''; ?>" readonly />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Tanggungan Orang Tua</label>
                <input type="number" name="jumlah_tanggungan" class="form-control" value="<?= isset($siswa) ? $siswa->jumlah_tanggungan : ''; ?>" readonly />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Kepemilikan Rumah</label>
                <input type="text" name="kepemilikan_rumah" class="form-control" value="<?= isset($siswa) ? $siswa->kepemilikan_rumah : ''; ?>" readonly />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Nilai Rata-Rata Rapor</label>
                <input type="text" name="nilai_rapor" class="form-control" value="<?= isset($siswa) ? $siswa->nilai_rapor : ''; ?>" readonly />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload KIP:</label><br>
                <?php if (!empty($siswa->file_kip)) { ?>
                    <a href="<?= base_url('uploads/' . $siswa->file_kip); ?>" target="_blank" class="btn btn-info">Lihat File</a>
                <?php } else { ?>
                    <p class="text-muted">Tidak ada file diupload</p>
                <?php } ?>
            </div>
        </div>

        <div class="card-footer text-right">
            <?php if ($siswa) { ?>
                <a href="<?= base_url('Isi_data/edit/' . ($siswa->id ?? '')); ?>" class="btn btn-warning">
                    <i class="fa fa-edit"></i> Edit Data
                </a>
            <?php } else { ?>
                <a href="<?= base_url('Isi_data/create'); ?>" class="btn btn-success">
                    <i class="fa fa-plus"></i> Tambah Data
                </a>
            <?php } ?>
        </div>
    </div>
</div>


<?php $this->load->view('layouts/footer_admin'); ?>