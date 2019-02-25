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
<body style="color:#000066;">
<div id="apDiv1">
<p align="center"><span class="style9"><strong>RINCIAN UANG MUKA KEGIATAN RUTIN </strong><br>
    <span class="style3"><strong>PETRO KIMIA GRESIK</strong></span><br>
  <span>Jl. Jenderal Ahmad Yani - Gresik 61119<br><br>
<?php if ($Role == "Sekretariat") { ?>
<span>NO : <?= $model->id.'/'.$sekre->secretariat_code.'/' ?>
<?php } else if ($Role == "Seksi") { ?>
<span>NO : <?= $model->id.'/'.$seksiId->section_code.'/' ?>
<?php } ?>
<?php
$bulan = date('n');
$romawi = getRomawi($bulan);
echo $romawi .'/SKPG'; ?>
<?php echo '/'.date("Y"); ?>
</span><br>

<hr style="color:#000000;"></hr>

<p><strong><em>&nbsp;</em></strong></p>
<table>
    <tbody>
        <tr>
            <td width="200"><strong>Yang Mengajukan Ijin Kegiatan Rutin</strong></td>
        </tr>
        <tr>
            <td>Nama</td>
            <?php if ($Role == "Sekretariat") { ?>
                <td>:<?=$sekre->secretariat_name?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td>:<?=$seksiId->section_name?></td>
            <?php } ?>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <?php if ($Role == "Sekretariat") { ?>
                <td>: <?=$sekre->secretariat_code?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td>: <?=$seksiId->section_code?></td>
            <?php } ?>
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
            <?php if ($Role == "Super Admin") { ?>
                <td><?=$model->title?></td>
            <?php } else if ($Role == "Sekretariat") { ?>
                <td><?=$model->title?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td><?=$model->title?></td>
            <?php } ?>
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
            <?php if ($Role == "Sekretariat") { ?>
                <td>Rp.<?=$baru->secretariat_budget_value + $budget->budget_value_dp?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td>Rp.<?=$baru->section_budget_value + $budget->budget_value_dp?></td>
            <?php } ?>
        </tr>
        <tr>
            <td>Waktu Pelaksanaan</td>
            <td>:</td>
            <?php if ($Role == "Super Admin") { ?>
                <td><?=$model->date_start.' s/d '.$model->date_end?></td>
            <?php } else if ($Role == "Sekretariat") { ?>
                <td><?=$model->date_start.' s/d '.$model->date_end?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td><?=$model->date_start.' s/d '.$model->date_end?></td>
            <?php } ?>
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
            <?php if ($Role == "Super Admin") { ?>
                <td>Rp.<?=$budget->budget_value_dp?></td>
            <?php } else if ($Role == "Sekretariat") { ?>
                <td>Rp.<?=$budget->budget_value_dp?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td>Rp.<?=$budget->budget_value_dp?></td>
            <?php } ?>
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
            <?php if ($Role == "Super Admin") { ?>
                <td>Rp.<?=$budget->budget_value_sum?></td>
            <?php } else if ($Role == "Sekretariat") { ?>
                <td>Rp.<?=$budget->budget_value_sum?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td>Rp.<?=$budget->budget_value_sum?></td>
            <?php } ?>
        </tr>
        <tr>
            <td>Uang Muka Kegiatan Rutin</td>
            <td>:</td>
            <?php if ($Role == "Super Admin") { ?>
                <td>Rp.<?=$budget->budget_value_dp?><td>
            <?php } else if ($Role == "Sekretariat") { ?>
                <td>Rp.<?=$budget->budget_value_dp?><td>
            <?php } else if ($Role == "Seksi") { ?>
                <td>Rp.<?=$budget->budget_value_dp?><td>
            <?php } ?>
        </tr>
        <tr>
            <td>Nilai Anggaran Kegiatan</td>
            <td>:</td>
            <?php if ($Role == "Super Admin") { ?>
                <td>Rp.<?=$budget->budget_value_sum ?></td>
            <?php } else if ($Role == "Sekretariat") { ?>
                <td>Rp.<?=$budget->budget_value_sum ?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td>Rp.<?=$budget->budget_value_sum ?></td>
            <?php } ?>
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
            <?php if ($Role == "Sekretariat") { ?>
                <td>Rp.<?=$budget->budget_value_sum-$budget->budget_value_dp?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td>Rp.<?=$budget->budget_value_sum-$budget->budget_value_dp?></td>
            <?php } ?>
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
<?php
function getRomawi($bln){
                switch ($bln){
                    case 1:
                        return "I";
                        break;
                    case 2:
                        return "II";
                        break;
                    case 3:
                        return "III";
                        break;
                    case 4:
                        return "IV";
                        break;
                    case 5:
                        return "V";
                        break;
                    case 6:
                        return "VI";
                        break;
                    case 7:
                        return "VII";
                        break;
                    case 8:
                        return "VIII";
                        break;
                    case 9:
                        return "IX";
                        break;
                    case 10:
                        return "X";
                        break;
                    case 11:
                        return "XI";
                        break;
                    case 12:
                        return "XII";
                        break;
                }
}
?>
</html>
