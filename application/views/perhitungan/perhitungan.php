<?php
$this->load->view('layouts/header_admin');
// Matrix Keputusan (X)
$matriks_x = array();
foreach ($kriterias as $kriteria) {
    foreach ($alternatifs as $alternatif) {
        $id_alternatif = $alternatif->id_alternatif;
        $id_kriteria = $kriteria->id_kriteria;

        $data_pencocokan = $this->Perhitungan_model->data_nilai($id_alternatif, $id_kriteria);
        $nilai = isset($data_pencocokan['nilai']) ? $data_pencocokan['nilai'] : 0;

        $matriks_x[$id_kriteria][$id_alternatif] = $nilai;
    }
}

// Normalisasi Matriks (Kij)
// Matriks Ternormalisasi (Kij)
$matriks_k = array();
$total_k = array();

foreach ($kriterias as $kriteria):
    $id_kriteria = $kriteria->id_kriteria;
    $jenis = strtolower($kriteria->jenis); // 'benefit' atau 'cost'
    $t_r = 0;

    // Ambil semua nilai untuk kriteria ini agar bisa hitung min/max
    $nilai_kriteria = array_column($matriks_x[$id_kriteria], null);

    $max = max($nilai_kriteria);
    $min = min($nilai_kriteria);

    foreach ($alternatifs as $alternatif):
        $id_alternatif = $alternatif->id_alternatif;
        $nilai_x = $matriks_x[$id_kriteria][$id_alternatif];

        if ($jenis == 'benefit') {
            $nilai_r = $nilai_x / $max;
        } elseif ($jenis == 'cost') {
            $nilai_r = $min / $nilai_x;
        } else {
            $nilai_r = 0; // fallback
        }

        $matriks_k[$id_kriteria][$id_alternatif] = round($nilai_r, 6); // pembulatan opsional
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

// perhitungan logaritma natural dari rasio
$ln_ratio = array();

foreach ($kriterias as $kriteria) {
    $id_kriteria = $kriteria->id_kriteria;

    foreach ($alternatifs as $alternatif) {
        $id_alternatif = $alternatif->id_alternatif;

        $dik = $matriks_a[$id_kriteria][$id_alternatif];

        // Entropy log calculation: e_ik = dik * ln(dik)
        $ln = ($dik > 0) ? round(log($dik), 5) : 0;

        $ln_ratio[$id_kriteria][$id_alternatif] = $ln;
    }
}

//Perhitungan nilai entropy untuk setiap kriteria
$nilai_e = array();
$total_e = array();
$entropy = array();
$total_ent = 0;
foreach ($kriterias as $kriteria):
    $t_e = 0;
    $id_kriteria = $kriteria->id_kriteria;
    foreach ($alternatifs as $alternatif):
        $id_alternatif = $alternatif->id_alternatif;

        $nilai_a = $matriks_a[$id_kriteria][$id_alternatif];

        // Menghitung nilai e, pastikan tidak menghasilkan NaN
        $e = !is_nan($nilai_a * log($nilai_a)) ? $nilai_a * log($nilai_a) : 0;

        $nilai_e[$id_kriteria][$id_alternatif] = $e;
        $t_e += $e;
    endforeach;

    $total_e[$id_kriteria] = $t_e;

    // Menghitung entropy dengan menghindari pembagian dengan nol
    if (count($alternatifs) > 1) {
        $entropy[$id_kriteria] = round(-0.434294 * $t_e, 5);
    } else {
        // Jika hanya ada satu alternatif, set entropy ke 0 (atau nilai lain yang sesuai)
        $entropy[$id_kriteria] = 0;
    }
    $total_ent += $entropy[$id_kriteria];
endforeach;

$count_kriterias = count($kriterias);
$nilai_edk = 1 / ($count_kriterias - $total_ent);
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

$bobot_e = array();
foreach ($kriterias as $kriteria):
    $id_kriteria = $kriteria->id_kriteria;
    $bobot_e[$id_kriteria] = $nilai_edk * round($nilai_d[$id_kriteria], 5);
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
                                    <th>
                                        <?= $kriteria->kode_kriteria ?>
                                    </th>
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
                                        echo round($matriks_a[$id_kriteria][$id_alternatif], 6);
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

            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Nilai logaritma natural dari
                    rasio</h6>
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
                                        echo $ln_ratio[$id_kriteria][$id_alternatif];
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
                                        echo round($nilai_e[$id_kriteria][$id_alternatif], 5);
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
                                    echo round($total_e[$id_kriteria], 5);
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
                                    echo round($entropy[$id_kriteria], 5);
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
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Derajat Ketidakacakan Informasi
                    (Diversitas
                    Informasi)</h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th></th>

                                <?php foreach ($kriterias as $kriteria): ?>
                                    <th><?= $kriteria->kode_kriteria ?></th>
                                <?php endforeach ?>

                            </tr>
                        </thead>
                        <tbody>
                            <tr align="center">
                                <td></td>
                                <?php foreach ($kriterias as $kriteria):
                                    $id_kriteria = $kriteria->id_kriteria;
                                    ?>
                                    <td><?php echo round($nilai_d[$id_kriteria], 5); ?></td>
                                <?php endforeach ?>

                            </tr>
                            <tr align="center">
                                <td class="bg-light"> BOBOT ENTROPY</td>
                                <?php foreach ($kriterias as $kriteria):
                                    $id_kriteria = $kriteria->id_kriteria;
                                    ?>
                                    <td class="bg-light"><?php echo round($bobot_e[$id_kriteria], 5); ?></td>
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
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Proses Perhitungan Bobot Akhir
                    Kriteria
                </h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Kode Kriteria</th>
                                <th>Bobot Entropy</th>
                                <th>Bobot Awal</th>
                                <th>Hasil</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            $total_hasil_kriteria = 0;
                            foreach ($kriterias as $kriteria):
                                $id_kriteria = $kriteria->id_kriteria;
                                $hasil = round($bobot_e[$id_kriteria], 5) * $kriteria->bobot;
                                $total_hasil_kriteria += $hasil;
                                ?>
                                <tr align="center">
                                    <td><?= $no; ?></td>
                                    <td><?php echo $kriteria->kode_kriteria ?></td>
                                    <td><?php echo round($bobot_e[$id_kriteria], 5) ?></td>
                                    <td><?php echo $kriteria->bobot ?></td>
                                    <td><?= $hasil ?></td>
                                </tr>
                                <?php
                                $no++;
                            endforeach

                            ?>
                            <tr class="bg-light">
                                <td colspan="4" style="text-align: right;">Total</td>
                                <td style="text-align: center;"><?= $total_hasil_kriteria ?></td>
                            </tr>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <!-- /.card-header -->
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-danger"><i class="fa fa-table"></i> Proses Perhitungan Bobot Akhir
                    Kriteria
                </h6>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead class="bg-danger text-white">
                            <tr align="center">
                                <th width="5%" rowspan="2">No</th>
                                <th>Kode Kriteria</th>
                                <th>Bobot Akhir</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;

                            foreach ($kriterias as $kriteria):
                                $id_kriteria = $kriteria->id_kriteria;
                                $bobot_akhir = (round($bobot_e[$id_kriteria], 5) * $kriteria->bobot) / $total_hasil_kriteria;

                                ?>
                                <tr align="center">
                                    <td><?= $no; ?></td>
                                    <td><?php echo $kriteria->kode_kriteria ?></td>
                                    <td><?php echo round($bobot_akhir, 5) ?></td>

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