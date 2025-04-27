<?php $this->load->view('layouts/header_admin'); ?>

<style>
.highlight {
    background-color: #ffeb3b;

}
</style>
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-chart-area"></i> Data Hasil Akhir</h1>

    <a href="<?= base_url('Laporan'); ?>" class="btn btn-primary"> <i class="fa fa-print"></i> Cetak Data </a>
</div>

<div class="card shadow mb-4">
    <!-- /.card-header -->
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Hasil Akhir Perankingan</h6>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" width="100%" cellspacing="0">
                <thead class="bg-danger text-white">
                    <tr align="center">
                        <th>Nama Alternatif</th>
                        <th>Nilai</th>
                        <th width="15%">Ranking</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
					$no = 1;
					foreach ($hasil as $keys):
						$user = $this->db->get_where('siswa', ['id' => $keys->siswa_id])->row();
						// Memeriksa apakah user_id sama dengan auth()->id()
						$highlightClass = ($user->user_id == $this->session->userdata('id_user')) ? 'highlight' : '';

						?>
                    <tr align="center" class="<?= $highlightClass ?>">
                        <td align="left"><?= $keys->nama ?></td>
                        <td><?= $keys->nilai ?></td>
                        <td><?= $no; ?></td>
                    </tr>
                    <?php
						$no++;
					endforeach ?>
                </tbody>

            </table>
        </div>
    </div>
</div>

<?php
$this->load->view('layouts/footer_admin');
?>