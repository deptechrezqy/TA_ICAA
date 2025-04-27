<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cube"></i> Data Siswa</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fas fa-fw fa-plus"></i>Tambah Data Siswa</h6>
    </div>

    <?php echo form_open_multipart('Isi_data/store'); ?>
    <div class="card-body">
        <?php if (validation_errors()) { ?>
        <div class="alert alert-danger">
            <?php echo validation_errors(); ?>
        </div>
        <?php } ?>

        <?php if ($this->session->flashdata('error')) { ?>
        <div class="alert alert-danger">
            <?php echo $this->session->flashdata('error'); ?>
        </div>
        <?php } ?>
        <div class="row">
            <div class="form-group col-md-4">
                <label class="font-weight-bold">NISN</label>
                <input autocomplete="off" type="text" name="nisn" required class="form-control" />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Nama Siswa</label>
                <input autocomplete="off" type="text" name="nama" required class="form-control" />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Penghasilan Orang Tua</label>
                <select name="penghasilan_ortu" class="form-control" required>
                    <option value="">-- Pilih Besar Penghasilan --</option>
                    <?php foreach ($penghasilan_ortu as $p) { ?>
                    <option value="<?php echo $p; ?>"><?php echo $p; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Tanggungan Orang Tua</label>
                <input autocomplete="off" type="number" name="jumlah_tanggungan" required class="form-control" />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Kepemilikan Rumah</label>
                <select name="kepemilikan_rumah" class="form-control" required>
                    <option value="">-- Pilih Status Kepemilikan --</option>
                    <?php foreach ($kepemilikan_rumah as $kr) { ?>
                    <option value="<?php echo $kr; ?>"><?php echo $kr; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Nilai Rata-Rata Rapor</label>
                <input autocomplete="off" type="text" name="nilai_rapor" required class="form-control" />
            </div>
            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload KIP:</label>
                <input type="file" name="file_kip" class="form-control" required>
            </div>
            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload Penghasilan Orang Tua:</label>
                <input type="file" name="file_penghasilan_orang_tua" class="form-control" required>
            </div>
            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload Tanggungan Orang Tua:</label>
                <input type="file" name="file_tanggungan_orang_tua" class="form-control" required>
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload Kepemilikan Rumah:</label>
                <input type="file" name="file_rumah" class="form-control" required>
            </div>
            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload Rapor:</label>
                <input type="file" name="file_rapor" class="form-control" required>
            </div>
        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
            <button type="reset" class="btn btn-info"><i class="fa fa-sync-alt"></i> Reset</button>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>
<?php $this->load->view('layouts/footer_admin'); ?>