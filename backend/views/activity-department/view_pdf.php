<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;
use kartik\mpdf\Pdf;
use common\models\ActivitySectionMember;

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
    @page {
              size: 29.7cm 21cm  portrait;   /*A4*/
              padding:0; margin:1; 
              top:0; left:0; right:0;bottom:0; border:0;
          }

          @media print {
              .table{
                margin-bottom: 0px;
              }
          }
    }
    -->
    </style>
</head>
<body>
<p align="center"><strong>RINCIAN UANG MUKA KEGIATAN </strong><br>
    <span class="style3"><strong>PETRO KIMIA GRESIK</strong></span><br>
  <span>Jl. Jenderal Ahmad Yani - Gresik 61119<br><br>
<span>NO : 834932482342</span><br>

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
    </tbody>
</table>
<br>
<table>
    <tbody>
            <tr>
            <td width="200"><strong>Panitia Inti</strong></td>
            </tr>
            <tr>
                <td>Ketua</td>
                <td>: <?=$ketua->name_member?></td>
            </tr>
            <tr>
                <td>Wakil</td>
                <td>: <?=$wakil->name_member?></td>
            </tr>
            <tr>
                <td>Sekretaris</td>
                <td>: <?=$sekretaris->name_member?></td>
            </tr>
            <tr>
                <td>Bendahara</td>
                <td>: <?=$bendahara->name_member?></td>
            </tr>
    </tbody>
</table>
<br>
<table>
    <tbody>
            <tr>
            <td width="200"><strong>List Seksi</strong></td>
            </tr>
            <?php foreach ($section as $key => $value) {
            $sectionMember = ActivitySectionMember::find()->where(['section_activity_id'=>$value->id])->andWhere(['activity_id'=>$value->activity_id])->all();
            ?>
            <tr>
                <td>Bagian Seksi</td>
                <td>:</td>
                <td><?=$value->section_name?></td>
            </tr>
            <tr>
                <td>Nama Anggota Seksi</td>
                <td>:</td>
                <?php foreach ($sectionMember as $key => $value) { ?>
                    <td><?=$value->section_name_member?></td>
               <?php } ?>
            </tr>
            <tr>
                <td></td>
            </tr>
            <?php } ?>
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
                <td><?=$model->name_activity?></td>
        </tr>
        <tr>
            <td>Judul / Tema</td>
            <td>:</td>
                <td><?=$model->title?></td>
        </tr>
        <tr>
            <td>Latar Belakang</td>
            <td>:</td>
                <td><?=$model->background?></td>
        </tr>
        <tr>
            <td>Tujuan</td>
            <td>:</td>
                <td><?=$model->purpose?></td>
        </tr>
        <tr>
            <td>Sasaran Kegiatan</td>
            <td>:</td>
                <td><?=$model->target_activity?></td>
        </tr>
        <tr>
            <td>Tempat Pelaksanaan</td>
            <td>:</td>
            <td><?=$model->place_activity?></td>
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
                <td>Rp.<?=$anggaran?></td>
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
            <td>Uang Muka Kegiatan</td>
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
            <td>Uang Muka Kegiatan</td>
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
                <td>Rp.<?=$anggaran-$budget->budget_value_dp?></td>
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
