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
function to_rp($val)
{
    return "Rp " . number_format($val,0,',','.');
}
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
  <table class="table table-responsive" width="100%" border="1">
       <tbody>
        <tr>
          <td colspan="1" class="text-center"><img src="<?=Yii::getAlias('@web'); ?>/image/kop-lpj.png"></td>
          <td colspan="4" class="text-center" style="vertical-align: middle"><img src="<?=Yii::getAlias('@web'); ?>/image/text2.png">
            <p align="center">
            <span>Jl. Jenderal Ahmad Yani - Gresik 61119</span>
          </p>
          <p align="center">
            <span align="center">NO : UM-
            <?php 
            echo date("Y");
            echo date("m"); 
            echo '-'.$model->id
             ?>
            </span>
          </p>
          </td>
        </tr>
      </tbody>
    </table>
    
<p><strong><em>&nbsp;</em></strong></p>
<table>
    <tbody>
        <tr>
            <td width="200"><strong>Yang Mengajukan Ijin Kegiatan Rutin</strong></td>
        </tr>
        <tr>
            <td>Nama</td>
            <?php if ($Role == "Sekretariat") { ?>
            <td>: <?=$kodeid->secretariat_name?></td>
            <?php } else if ($Role == "Seksi") { ?>
            <td>: <?=$kodeid->section_name?></td>
            <?php } ?>
        </tr>
        <tr>
            <td>Unit Kerja</td>
            <?php if ($Role == "Sekretariat") { ?>
            <td>: <?=$kodeid->secretariat_code?></td>
            <?php } else if ($Role == "Seksi") { ?>
            <td>: <?=$kodeid->section_code?></td>
            <?php } ?>

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
                <?php foreach ($sectionMember as $key => $value) {
                    if($key == 0){ ?>
                      <td><?=$value->section_name_member?></td>
                    <?php }else{ ?>
                      <tr>
                      <td></td>
                      <td></td>
                      <td><?=$value->section_name_member?></td>
                      </tr>
                    <?php }
                   ?>
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
            <?php if ($Role == "Super Admin") { ?>
                <td><?=$model->name_activity?></td>
            <?php } else if ($Role == "Sekretariat") { ?>
                <td><?=$model->name_activity?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td><?=$model->title?></td>
            <?php } ?>
        </tr>
        <tr>
            <td>Judul / Tema</td>
            <td>:</td>
            <?php if ($Role == "Super Admin") { ?>
                <td><?=$model->title?></td>
            <?php } else if ($Role == "Sekretariat") { ?>
                <td><?=$model->title?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td><?=$model->title?></td>
            <?php } ?>
        </tr>
        <tr>
            <td valign="top">Latar Belakang</td>
            <td>:</td>
            <?php if ($Role == "Sekretariat") { ?>
                <td><?=$model->background?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td><?=$model->background?></td>
            <?php } ?>
        </tr>
        <tr>
            <td>Tujuan</td>
            <td>:</td>
            <?php if ($Role == "Sekretariat") { ?>
                <td><?=$model->purpose?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td><?=$model->purpose?></td>
            <?php } ?>
        </tr>
        <tr>
            <td>Sasaran Kegiatan</td>
            <td>:</td>
            <?php if ($Role == "Sekretariat") { ?>
                <td><?=$model->target_activity?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td><?=$model->target_activity?></td>
            <?php } ?>
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
            <td>Sisa Anggaran Saat Ini</td>
            <?php if ($Role == "Sekretariat") { ?>
                <td><?=to_rp($baru->secretariat_budget_value)?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td><?=to_rp($baru->section_budget_value)?></td>
            <?php } ?>
        </tr>
        <tr>
            <td>Waktu Pelaksanaan</td>
            <td>:</td>
            <?php if ($Role == "Sekretariat") { ?>
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
            <td>Uang Muka Kegiatan</td>
            <?php if ($Role == "Sekretariat") { ?>
                <td><?=to_rp($budget->budget_value_sum)?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td><?=to_rp($budget->budget_value_sum)?></td>
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
        </tr>
        <tr>
            <td>Uang Muka Kegiatan</td>
            <td>:</td>
            <?php if ($Role == "Sekretariat") { ?>
                <td><?=to_rp($budget->budget_value_sum) ?></td>
            <?php } else if ($Role == "Seksi") { ?>
                <td><?=to_rp($budget->budget_value_sum) ?></td>
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
