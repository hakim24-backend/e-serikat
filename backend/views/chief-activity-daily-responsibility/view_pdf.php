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
$date = date('Y-m-d');

$Role = Yii::$app->user->identity->roleName();
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
      <section id="core" style="width: 100%;">
      <div class="center-content">
         <img src="<?=Yii::getAlias('@web'); ?>/image/kop-lpj.png">
         <table>
           <tbody>
             <tr style="border-bottom-style: hidden;">
              <td colspan="2" style="border-right-style: hidden;">Nama</td>
              <td style="border-right-style: hidden;">:</td>
              <td colspan="3"><?=$sekre->chief_name?></td>
            </tr>
            <tr style="border-bottom-style: hidden;">
              <td colspan="2" style="border-right-style: hidden;">Seksi/Departemen</td>
              <td style="border-right-style: hidden;">:</td>
              <td colspan="3"><?=$sekre->chief_code?></td>
            </tr>
           </tbody>
         </table>
         <br>
         <table class="table table-responsive" width="100%" border="1">
           <tbody>
             <tr>
              <td rowspan="2" class="text-center" style="vertical-align: middle;">No.</td>
              <td rowspan="2" colspan="2" class="text-center" style="vertical-align: middle;">Uraian</td>
              <td colspan="2" class="text-center">Pengeluaran</td>
            </tr>
             <tr>
              <td class="text-center">Rencana</td>
              <td class="text-center">Realisasi</td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;">1</td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"><?=$model->title?></td>
              <td class="text-center" style="border-bottom-style: hidden;"><?=$budget->budget_value_sum?></td>
              <td class="text-center" style="border-bottom-style: hidden;"><?=$budget->budget_value_dp?></td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td colspan="2" class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
              <td class="text-center" style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td class="text-center"></td>
              <td colspan="2" class="text-center"></td>
              <td class="text-center"></td>
              <td class="text-center"></td>
            </tr>
           </tbody>
           <tbody>
            <tr>
              <td colspan="4">Jumlah Uang yang dipertanggung jawabkan.</td>
              <td class="text-center"><?=$budget->budget_value_sum?></td>
            </tr>
            <tr>
              <td colspan="4">Jumlah Uang yang Diterima.</td>
              <td class="text-center"><?=$budget->budget_value_sum - $budget->budget_value_dp?></td>
            </tr>
            <tr>
              <td colspan="4">Kekurangan / Sisa Uang Muka.</td>
              <td class="text-center"><?=$baru->chief_budget_value?></td>
            </tr>
            <tr>
              <td colspan="5">Gresik, <?=$date?></td>
            </tr>
          </tbody>
         </table>
         <table class="table table-responsive" width="100%" border="1">
          <tbody>
             <tr>
              <td colspan="1" rowspan="2" width="16%" class="text-center">Yang mempertanggung jawabkan</td>
              <td colspan="4" class="text-center">Menyetujui</td>
            </tr>
            <tr>
              <td width="21%">KADEP</td>
              <td class="text-center" width="21%">KETUA II </td>
              <td class="text-center" width="21%">SEKRETARIS UMUM </td>
              <td class="text-center" width="21%">BENDAHARA </td>
            </tr>
            <tr>
              <td class="text-center" style="border-bottom-style: hidden;">Ketua Seksi</td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
              <td style="border-bottom-style: hidden;"></td>
            </tr>
            <tr>
              <td></td>
              <td></td>
              <td class="text-center"><div style="margin-top: 100px;"><p style="font-size: 11.5px;">Walyo Sirdjo</p></div></td>
              <td class="text-center"><div style="margin-top: 100px;"><p style="font-size: 11.5px;">Abdul Rohman Wachid</p></div></td>
              <td></td>
            </tr>
            <tr>
              <td colspan="6"><b>CATATAN :</b><br>Apabila pertanggung jawaban melebihi waktu yang telah ditentukan, maka sementara kegiatan terkait dibulan selanjutnya tidak diberikan Uang Muka sampai dengan pertanggung jawaban diselesaikan.</td>
            </tr>
          </tbody>
         </table>
      </div>
    </section>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
  </body>
    </body>
</html>
