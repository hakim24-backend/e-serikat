<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\mpdf\Pdf;

/* @var $this yii\web\View */
/* @var $model common\models\ActivityDaily */

$this->title = 'Data Kegiatan Rutin Sekretariat';
$this->params['breadcrumbs'][] = ['label' => 'Activity Dailies', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$Role = Yii::$app->user->identity->roleName();
$date = date('Y-m-d');
?>

<html>
<head>
<style type="text/css">
<!--
#apDiv1 {
    position:absolute;
    left:136px;
    top:16px;
    width:431px;
    height:116px;
    z-index:1;
}
#apDiv2 {
    position:absolute;
    left:635px;
    top:180px;
    width:63px;
    height:32px;
    z-index:2;
}
.style3 {
    font-size: 20px;
    font-weight: bold;
}
.style9 {font-size: 15px}
.style10 {font-size: 15px}
#apDiv3 {
    position:absolute;
    left:607px;
    top:205px;
    width:86px;
    height:35px;
    z-index:2;
}
#apDiv4 {
    position:absolute;
    left:78px;
    top:163px;
    width:534px;
    height:111px;
    z-index:3;
}
#apDiv5 {
    position:absolute;
    left:527px;
    top:204px;
    width:73px;
    height:57px;
    z-index:4;
}
-->
</style>
</head>
<body style="color:#000066;">
<div id="apDiv1">
<p align="center"><span class="style9"><strong>RINCIAN UANG MUKA KEGIATAN RUTIN </strong><br>
    <span class="style3"><strong>PETRO KIMIA GRESIK</strong></span><br>
  <span>Jl. Raya Gili Timur, Bandung Barat, Keleyan, Socah, Kabupaten Bangkalan, Jawa Timur 69161<br><br>
<span>NO : 834932482342</span><br>
<span>NO : 234248244244</span>

<hr style="color:#000000;"></hr>

<p><strong><em>&nbsp;</em></strong></p>
<table>
    <tbody>
        <tr>
            <td width="200"><strong>Yang Mengajukan Ijin Kegiatan Rutin</strong></td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>: <?=Yii::$app->user->identity->username?></td>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <td>: <?=$sekre->depart_code?></td>
        </tr>
        <tr>
            <td>Nomor Rekening</td>
            <td>: <?=$sumber->budget_rek?></td>
        </tr>
    </tbody>
</table>
<br>
<table>
    <tbody>
        <tr>
            <td width="200"><strong>Kode Anggaran</strong></td>
        </tr>
        <tr>
            <td>Kode Anggaran Pengelola</td>
            <td>:</td>
            <td><?=$sumber->budget_code?></td>
        </tr>
        <tr>
            <td>Kode Anggaran Penerima</td>
            <td>:</td>
            <td><?=$baru->department_budget_code?></td>
        </tr>
    </tbody>
</table>
<br>
<table>
    <tbody>
        <tr>
            <td><strong>Rencana Kegiatan</strong></td>
        </tr>
        <tr>
            <td>Nama Kegiatan</td>
            <td>:</td>
            <td><?=$model->title?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Anggaran Saat Ini</td>
            <td>Rp.<?=$baru->department_budget_value?></td>
        </tr>
        <tr>
            <td>Waktu Pelaksanaan</td>
            <td>:</td>
            <td><?=$model->date_start.' s/d '.$model->date_end?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Uang Muka Kegiatan Rutin</td>
            <td>Rp.<?=$budget->budget_value_dp?></td>
        </tr>
        <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Nilai Anggaran</td>
            <td>Rp.<?=$budget->budget_value_sum?></td>
        </tr>
        <tr>
            <td>Uang Muka Kegiatan Rutin</td>
            <td>:</td>
            <td>Rp.<?=$budget->budget_value_dp?><td>
        </tr>
        <tr>
            <td>Nilai Anggaran Kegiatan</td>
            <td>:</td>
            <td>Rp.<?=$budget->budget_value_sum ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>Sisa Nilai Anggaran Saat Ini</td>
            <td>Rp.<?=$baru->department_budget_value - $budget->budget_value_dp?></td>
        </tr>
    </tbody>
</table>
<br>
<table>
    <tbody>
        <tr>
            <td><strong>Diajukan Oleh</strong></td>
        </tr>
        <br>
        <br>
        <br>
        <tr>
            <td><?=Yii::$app->user->identity->username?></td>
        </tr>
    </tbody>
</table>
<br>
<table>
    <tbody>
        <tr>
            <td><strong>Disetujui Oleh</td>
        </tr>
        <tr>
            <td width="270">Tanggal, <?=$date?></td>
            <td width="270">Tanggal, <?=$date?></td>
            <td>Tanggal, <?=$date?></td>
        </tr>
    </tbody>
</table>
<table>
    <tbody>
        <tr>
            <td width="270">Telah disetujui sistem</td>
            <td width="270">Tidak diperlukan</td>
            <td>Tidak diperlukan</td>
        </tr>
    </tbody>
</table>
<br>
<br>
<br>
<table>
    <tbody>
        <tr>
            <td width="270">____________________</td>
            <td width="270">____________________</td>
            <td>____________________</td>
        </tr>
        <tr>
            <td></td>
        </tr>
    </tbody>
</table>
</body>
</html>
