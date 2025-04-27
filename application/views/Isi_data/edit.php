<?php $this->load->view('layouts/header_admin'); ?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-cube"></i> Edit Data Siswa</h1>
</div>

<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-fw fa-edit"></i> Edit Data Siswa</h6>
    </div>

    <?php echo form_open_multipart('Isi_data/update/' . $siswa->id); ?>
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
                <input autocomplete="off" type="text" name="nisn" value="<?php echo set_value('nisn', $siswa->nisn); ?>"
                    required class="form-control" />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Nama Siswa</label>
                <input autocomplete="off" type="text" name="nama" value="<?php echo set_value('nama', $siswa->nama); ?>"
                    required class="form-control" />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Penghasilan Orang Tua</label>
                <select name="penghasilan_ortu" class="form-control" required>
                    <option value="">-- Pilih Besar Penghasilan --</option>
                    <?php foreach ($penghasilan_ortu as $p) { ?>
                    <option value="<?php echo $p; ?>" <?php echo ($siswa->penghasilan_ortu == $p) ? 'selected' : ''; ?>>
                        <?php echo $p; ?>
                    </option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Tanggungan Orang Tua</label>
                <input autocomplete="off" type="number" name="jumlah_tanggungan"
                    value="<?php echo set_value('jumlah_tanggungan', $siswa->jumlah_tanggungan); ?>" required
                    class="form-control" />
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Kepemilikan Rumah</label>
                <select name="kepemilikan_rumah" class="form-control" required>
                    <option value="">-- Pilih Status Kepemilikan --</option>
                    <?php foreach ($kepemilikan_rumah as $kr) { ?>
                    <option value="<?php echo $kr; ?>"
                        <?php echo ($siswa->kepemilikan_rumah == $kr) ? 'selected' : ''; ?>><?php echo $kr; ?></option>
                    <?php } ?>
                </select>
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Nilai Rata-Rata Rapor</label>
                <input autocomplete="off" type="text" name="nilai_rapor"
                    value="<?php echo set_value('nilai_rapor', $siswa->nilai_rapor); ?>" required
                    class="form-control" />
            </div>

            <!-- Upload File Section -->
            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload KIP:</label>
                <input type="file" name="file_kip" class="form-control">
                <?php if (!empty($siswa->file_kip)) { ?>
                <small class="text-muted">File saat ini: <a
                        href="<?php echo base_url('public/uploads/' . $siswa->file_kip); ?>"
                        target="_blank">Lihat</a></small>
                <input type="hidden" name="file_kip_lama" value="<?php echo $siswa->file_kip; ?>">
                <?php } ?>
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload Penghasilan Orang Tua:</label>
                <input type="file" name="file_penghasilan_orang_tua" class="form-control">
                <?php if (!empty($siswa->file_penghasilan_orang_tua)) { ?>
                <small class="text-muted">File saat ini: <a
                        href="<?php echo base_url('public/uploads/' . $siswa->file_penghasilan_orang_tua); ?>"
                        target="_blank">Lihat</a></small>
                <input type="hidden" name="file_penghasilan_orang_tua_lama"
                    value="<?php echo $siswa->file_penghasilan_orang_tua; ?>">
                <?php } ?>
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload Tanggungan Orang Tua:</label>
                <input type="file" name="file_tanggungan_orang_tua" class="form-control">
                <?php if (!empty($siswa->file_tanggungan_orang_tua)) { ?>
                <small class="text-muted">File saat ini: <a
                        href="<?php echo base_url('public/uploads/' . $siswa->file_tanggungan_orang_tua); ?>"
                        target="_blank">Lihat</a></small>
                <input type="hidden" name="file_tanggungan_orang_tua_lama"
                    value="<?php echo $siswa->file_tanggungan_orang_tua; ?>">
                <?php } ?>
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload Kepemilikan Rumah:</label>
                <input type="file" name="file_rumah" class="form-control">
                <?php if (!empty($siswa->file_rumah)) { ?>
                <small class="text-muted">File saat ini: <a
                        href="<?php echo base_url('public/uploads/' . $siswa->file_rumah); ?>"
                        target="_blank">Lihat</a></small>
                <input type="hidden" name="file_rumah_lama" value="<?php echo $siswa->file_rumah; ?>">
                <?php } ?>
            </div>

            <div class="form-group col-md-4">
                <label class="font-weight-bold">Upload Rapor:</label>
                <input type="file" name="file_rapor" class="form-control">
                <?php if (!empty($siswa->file_rapor)) { ?>
                <small class="text-muted">File saat ini: <a
                        href="<?php echo base_url('public/uploads/' . $siswa->file_rapor); ?>"
                        target="_blank">Lihat</a></small>
                <input type="hidden" name="file_rapor_lama" value="<?php echo $siswa->file_rapor; ?>">
                <?php } ?>
            </div>

        </div>
        <div class="card-footer text-right">
            <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Simpan</button>
            <a href="<?php echo base_url('Isi_data'); ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i>
                Kembali</a>
        </div>
    </div>
    <?php echo form_close(); ?>
</div>

<?php $this->load->view('layouts/footer_admin'); ?>