<?php
$this->load->view('layouts/header_admin');
//Matrix Keputusan (X)
$matriks_x = array();
foreach ($kriterias as $kriteria):
	foreach ($alternatifs as $alternatif):
		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;

		$data_pencocokan = $this->Perhitungan_model->data_nilai($id_alternatif, $id_kriteria);
		$nilai = $data_pencocokan['nilai'];

		$matriks_x[$id_kriteria][$id_alternatif] = $nilai;
	endforeach;
endforeach;

// METODE ENTROPY

//Matriks Ternormalisasi (Kij)
$matriks_k = array();
$total_k = array();
foreach ($kriterias as $kriteria):
	$t_r = 0;
	$id_kriteria = $kriteria->id_kriteria;
	foreach ($alternatifs as $alternatif):
		$id_alternatif = $alternatif->id_alternatif;

		$nilai_x = $matriks_x[$id_kriteria][$id_alternatif];
		$max = max($matriks_x[$id_kriteria]);

		$nilai_r = $nilai_x / $max;
		$matriks_k[$id_kriteria][$id_alternatif] = $nilai_r;
		$t_r += $nilai_r;
	endforeach;
	$total_k[$id_kriteria] = $t_r;
endforeach;

//Matriks Ternormalisasi aij
$matriks_a = array();
foreach ($kriterias as $kriteria):
	foreach ($alternatifs as $alternatif):
		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;

		$nilai_r = $matriks_k[$id_kriteria][$id_alternatif];
		$t_r = $total_k[$id_kriteria];

		$nilai_a = $nilai_r / $t_r;
		$matriks_a[$id_kriteria][$id_alternatif] = $nilai_a;
	endforeach;
endforeach;

//Perhitungan nilai entropy untuk setiap kriteria
$nilai_e = array();
$total_e = array();
$entropy = array();
foreach ($kriterias as $kriteria):
	$t_e = 0;
	$id_kriteria = $kriteria->id_kriteria;
	foreach ($alternatifs as $alternatif):
		$id_alternatif = $alternatif->id_alternatif;

		$nilai_a = $matriks_a[$id_kriteria][$id_alternatif];
		$e = !is_nan($nilai_a * log($nilai_a)) ? $nilai_a * log($nilai_a) : 0;

		$nilai_e[$id_kriteria][$id_alternatif] = $e;
		$t_e += $e;
	endforeach;

	$total_e[$id_kriteria] = $t_e;
	$entropy[$id_kriteria] = (-1 / log(count($alternatifs))) * $t_e;
endforeach;

//Perhitungan dispresi untuk setiap kriteria 𝐷Dj
$nilai_d = array();
$total_d = 0;
foreach ($kriterias as $kriteria):
	$id_kriteria = $kriteria->id_kriteria;
	$ent = $entropy[$id_kriteria];
	$d = 1 - $ent;
	$nilai_d[$id_kriteria] = $d;
	$total_d += $d;
endforeach;

//Normalisasi nilai dispersi Wj
$nilai_w = array();
foreach ($kriterias as $kriteria):
	$id_kriteria = $kriteria->id_kriteria;
	$d = $nilai_d[$id_kriteria];
	$w = $d / $total_d;
	$nilai_w[$id_kriteria] = $w;
endforeach;


//METODE MOORA

//Matriks Ternormalisasi (R)
$matriks_r = array();
foreach ($matriks_x as $id_kriteria => $penilaians):

	$jumlah_kuadrat = 0;
	foreach ($penilaians as $penilaian):
		$jumlah_kuadrat += pow($penilaian, 2);
	endforeach;
	$akar_kuadrat = sqrt($jumlah_kuadrat);

	foreach ($penilaians as $id_alternatif => $penilaian):
		$matriks_r[$id_kriteria][$id_alternatif] = $penilaian / $akar_kuadrat;
	endforeach;

endforeach;

//Matriks Normalisasi Terbobot
$matriks_rb = array();
foreach ($alternatifs as $alternatif):
	foreach ($kriterias as $kriteria):

		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;
		$bobot = $nilai_w[$id_kriteria];

		$nilai_r = $matriks_r[$id_kriteria][$id_alternatif];
		$matriks_rb[$id_kriteria][$id_alternatif] = $bobot * $nilai_r;

	endforeach;
endforeach;

//Nilai Yi
$nilai_y_max = array();
$nilai_y_min = array();
foreach ($alternatifs as $alternatif):
	$total_max = 0;
	$total_min = 0;
	foreach ($kriterias as $kriteria):

		$id_alternatif = $alternatif->id_alternatif;
		$id_kriteria = $kriteria->id_kriteria;
		$type_kriteria = $kriteria->jenis;

		$nilai_rb = $matriks_rb[$id_kriteria][$id_alternatif];

		if ($type_kriteria == 'Benefit'):
			$total_max += $nilai_rb;
		elseif ($type_kriteria == 'Cost'):
			$total_min += $nilai_rb;
		endif;
	endforeach;
	$nilai_y_max[$id_kriteria][$id_alternatif] = $total_max;
	$nilai_y_min[$id_kriteria][$id_alternatif] = $total_min;
endforeach;

?>

<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800"><i class="fas fa-fw fa-calculator"></i> Data Perhitungan</h1>
</div>

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
        <a class="nav-item nav-link active w-50" id="nav-entropy-tab" data-toggle="tab" href="#nav-entropy" role="tab"
            aria-controls="nav-entropy" aria-selected="true">Metode ENTROPY</a>
        <a class="nav-item nav-link w-50" id="nav-moora-tab" data-toggle="tab" href="#nav-moora" role="tab"
            aria-controls="nav-moora" aria-selected="false">Metode MOORA</a>
    </div>
</nav>
<div class="tab-content mt-4" id="nav-tabContent">
    <div class="tab-pane fade show active" id="nav-entropy" role="tabpanel" aria-labelledby="nav-entropy-tab">
        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Matrix Keputusan (X)</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Nama Alternatif</th>
                                <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$no = 1;
							foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php
									foreach ($kriterias as $kriteria):
										$id_alternatif = $alternatif->id_alternatif;
										$id_kriteria = $kriteria->id_kriteria;
										echo '<td>';
										echo $matriks_x[$id_kriteria][$id_alternatif];
										echo '</td>';
									endforeach
									?>
                            </tr>
                            <?php
								$no++;
							endforeach
							?>
                            <tr align="center">
                                <td class="bg-light" colspan="2">MAX</td>
                                <?php
								foreach ($kriterias as $kriteria):
									$id_kriteria = $kriteria->id_kriteria;
									echo '<td class="bg-light">';
									echo max($matriks_x[$id_kriteria]);
									echo '</td>';
								endforeach
								?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Matriks Normalisasi (Kij)</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Nama Alternatif</th>
                                <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$no = 1;
							foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php
									foreach ($kriterias as $kriteria):
										$id_alternatif = $alternatif->id_alternatif;
										$id_kriteria = $kriteria->id_kriteria;
										echo '<td>';
										echo $matriks_k[$id_kriteria][$id_alternatif];
										echo '</td>';
									endforeach;
									?>
                            </tr>
                            <?php
								$no++;
							endforeach
							?>
                            <tr align="center">
                                <td class="bg-light" colspan="2">TOTAL</td>
                                <?php
								foreach ($kriterias as $kriteria):
									$id_kriteria = $kriteria->id_kriteria;
									echo '<td class="bg-light">';
									echo $total_k[$id_kriteria];
									echo '</td>';
								endforeach
								?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Matriks aij</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Nama Alternatif</th>
                                <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$no = 1;
							foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php
									foreach ($kriterias as $kriteria):
										$id_alternatif = $alternatif->id_alternatif;
										$id_kriteria = $kriteria->id_kriteria;
										echo '<td>';
										echo $matriks_a[$id_kriteria][$id_alternatif];
										echo '</td>';
									endforeach;
									?>
                            </tr>
                            <?php
								$no++;
							endforeach
							?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Nilai Entropy (Ej)</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Nama Alternatif</th>
                                <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$no = 1;
							foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php
									foreach ($kriterias as $kriteria):
										$id_alternatif = $alternatif->id_alternatif;
										$id_kriteria = $kriteria->id_kriteria;
										echo '<td>';
										echo $nilai_e[$id_kriteria][$id_alternatif];
										echo '</td>';
									endforeach;
									?>
                            </tr>
                            <?php
								$no++;
							endforeach
							?>
                            <tr align="center">
                                <td class="bg-light" colspan="2">TOTAL</td>
                                <?php
								foreach ($kriterias as $kriteria):
									$id_kriteria = $kriteria->id_kriteria;
									echo '<td class="bg-light">';
									echo $total_e[$id_kriteria];
									echo '</td>';
								endforeach
								?>
                            </tr>
                            <tr align="center">
                                <td class="bg-light" colspan="2">ENTROPY</td>
                                <?php
								foreach ($kriterias as $kriteria):
									$id_kriteria = $kriteria->id_kriteria;
									echo '<td class="bg-light">';
									echo $entropy[$id_kriteria];
									echo '</td>';
								endforeach
								?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Perhitungan Dispresi Kriteria
                    (Dj)</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach ?>
                                <th>TOTAL</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align="center">
                                <?php foreach ($kriterias as $kriteria):
									$id_kriteria = $kriteria->id_kriteria;
									?>
                                <td><?php echo $nilai_d[$id_kriteria]; ?></td>
                                <?php endforeach ?>
                                <td><?php echo $total_d; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Normalisasi Nilai Dispersi (Wj)
                </h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Kode Kriteria</th>
                                <th>Nama Kriteria</th>
                                <th>Jenis</th>
                                <th>Nilai Bobot (Wj)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$no = 1;
							foreach ($kriterias as $kriteria):
								$id_kriteria = $kriteria->id_kriteria;
								?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td><?php echo $kriteria->kode_kriteria ?></td>
                                <td><?php echo $kriteria->keterangan ?></td>
                                <td><?php echo $kriteria->jenis ?></td>
                                <?php
									echo '<td>';
									echo $nilai_w[$id_kriteria];
									echo '</td>';
									?>
                            </tr>
                            <?php
								$no++;
							endforeach
							?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-moora" role="tabpanel" aria-labelledby="nav-moora-tab">
        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Matrix Keputusan (X)</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Nama Alternatif</th>
                                <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$no = 1;
							foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php
									foreach ($kriterias as $kriteria):
										$id_alternatif = $alternatif->id_alternatif;
										$id_kriteria = $kriteria->id_kriteria;
										echo '<td>';
										echo $matriks_x[$id_kriteria][$id_alternatif];
										echo '</td>';
									endforeach
									?>
                            </tr>
                            <?php
								$no++;
							endforeach
							?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Bobot Preferensi (W)</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?> (<?= $kriteria->jenis ?>)</th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr align="center">
                                <?php
								foreach ($kriterias as $kriteria):
									$id_kriteria = $kriteria->id_kriteria;
									?>
                                <td>
                                    <?php
										echo $nilai_w[$id_kriteria];
										?>
                                </td>
                                <?php endforeach ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Matriks Ternormalisasi (R)</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Nama Alternatif</th>
                                <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$no = 1;
							foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php
									foreach ($kriterias as $kriteria):
										$id_alternatif = $alternatif->id_alternatif;
										$id_kriteria = $kriteria->id_kriteria;
										echo '<td>';
										echo $matriks_r[$id_kriteria][$id_alternatif];
										echo '</td>';
									endforeach;
									?>
                            </tr>
                            <?php
								$no++;
							endforeach
							?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Matriks Normalisasi Terbobot
                </h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Nama Alternatif</th>
                                <?php foreach ($kriterias as $kriteria): ?>
                                <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$no = 1;
							foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php
									foreach ($kriterias as $kriteria):
										$id_alternatif = $alternatif->id_alternatif;
										$id_kriteria = $kriteria->id_kriteria;
										echo '<td>';
										echo $matriks_rb[$id_kriteria][$id_alternatif];
										echo '</td>';
									endforeach;
									?>
                            </tr>
                            <?php
								$no++;
							endforeach
							?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Menghitung Nilai Yi</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Nama Alternatif</th>
                                <th>Maximun (
                                    <?php foreach ($kriterias as $kriteria):
										if ($kriteria->jenis == "Benefit") {
											echo $kriteria->kode_kriteria . " ";
										}
									endforeach
									?>)
                                </th>
                                <th>Minimum (
                                    <?php foreach ($kriterias as $kriteria):
										if ($kriteria->jenis == "Cost") {
											echo $kriteria->kode_kriteria . " ";
										}
									endforeach
									?>)
                                </th>
                                <th>Yi = Max - Min</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
							$no = 1;
							$this->Perhitungan_model->hapus_hasil();
							foreach ($alternatifs as $alternatif): ?>
                            <tr align="center">
                                <td><?= $no; ?></td>
                                <td align="left"><?= $alternatif->nama ?></td>
                                <?php
									$total_max = 0;
									$total_min = 0;
									foreach ($kriterias as $kriteria):
										$id_alternatif = $alternatif->id_alternatif;
										$id_kriteria = $kriteria->id_kriteria;
										$nilai_rb = $matriks_rb[$id_kriteria][$id_alternatif];
										if ($kriteria->jenis == "Benefit") {
											$total_max += $nilai_rb;
										} else {
											$total_min += $nilai_rb;
										}
									endforeach;
									?>
                                <td>
                                    <?= $total_max; ?>
                                </td>
                                <td>
                                    <?= $total_min; ?>
                                </td>
                                <td>
                                    <?= $hasil = $total_max - $total_min; ?>
                                </td>
                            </tr>
                            <?php
								$no++;
								$hasil_akhir = [
									'id_alternatif' => $alternatif->id_alternatif,
									'nilai' => $hasil
								];
								$this->Perhitungan_model->insert_hasil($hasil_akhir);
							endforeach
							?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>




<?php
$this->load->view('layouts/footer_admin');
?>