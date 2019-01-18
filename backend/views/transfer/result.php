<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\web\Session;
use yii\base\view;

$request = Yii::$app->request;
$this->title = 'Import Excel - Result';

$this->params['breadcrumbs'][] = ['label' => 'Data', 'url' => ['transfer/']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="transfer-import-view">
	<?php 
		$form = ActiveForm::begin([
           'options' => [
               'enctype' => 'multipart/form-data', 
               'class' => 'form-horizontal'
           ],
           'action' => ['transfer/save']
       ]);
	?>
	<div class="col-md-12">
	  	<div class="box box-info">
		    <div class="box-header">
		      <h3 class="box-title">Sekretariat</h3>
		    </div>
		    <div class="box-body">
		      <?php 
		      	if ($arrSekretariat) {
		      		for ($i=0; $i < count($arrSekretariat); $i++) { 
		      ?>
		      <div class="form-group">
		          <label for="inputKodeSekretariat" class="col-sm-2 control-label">Kode Sekretariat</label>

		          <div class="col-sm-10">
		              <input type="text" class="form-control" id="sekretariat-kode" placeholder="Kode Sekretariat" readonly="true" name="sekretariat[<?=$i?>][kode]" value="<?= $arrSekretariat[$i]['kode_sekretariat'] ?>">
		              <input type="hidden" class="form-control" id="sekretariat-id" placeholder="Sekretariat Id" name="sekretariat[<?=$i?>][id]" value="<?= $arrSekretariat[$i]['id_sekretariat'] ?>">
		          </div>
		      </div>
		      <div class="form-group">
		          <label for="inputKodeSumberSekretariat" class="col-sm-2 control-label">Kode Sumber Dana</label>

		          <div class="col-sm-10">
		              <input type="text" class="form-control" id="sekretariat-sumber" placeholder="Kode Sumber Dana" readonly="true" name="sekretariat[<?=$i?>][sumber]" value="<?= $arrSekretariat[$i]['sumber_dana'] ?>">
		          </div>
		      </div>
		      <div class="form-group">
		          <label for="inputAnggaranSekretariat" class="col-sm-2 control-label">Nilai Anggaran</label>

		          <div class="col-sm-10">
		              <input type="text" class="form-control" id="sekretariat-nilai" placeholder="Nilai Anggaran" readonly="true" name="sekretariat[<?=$i?>][nilai]" value="<?= $arrSekretariat[$i]['nilai'] ?>">
		          </div>
		      </div>

		      <hr>
		  	<?php }
		  	}
		  	 ?>
		    </div>
	    	<!-- /.box-body -->
	  	</div>

	  	<div class="box box-info">
		    <div class="box-header">
		      <h3 class="box-title">Ketua</h3>
		    </div>
		    <div class="box-body">
		    	<?php 
		      	if ($arrKetua) {
		      		for ($i=0; $i < count($arrKetua); $i++) { 
		      	?>
		      <div class="form-group">
		          <label for="inputKodeKetua" class="col-sm-2 control-label">Kode Ketua</label>

		          <div class="col-sm-10">
		              <input type="text" class="form-control" id="ketua-kode" placeholder="Email" readonly="true" name="ketua[<?=$i?>][kode]" value="<?= $arrKetua[$i]['kode_ketua'] ?>">
		              <input type="hidden" class="form-control" id="ketua-id" placeholder="Email" name="ketua[<?=$i?>][id]" value="<?= $arrKetua[$i]['id_ketua'] ?>">
		          </div>
		      </div>
		      <div class="form-group">
		          <label for="inputKodeSumberKetua" class="col-sm-2 control-label">Kode Sumber Dana</label>

		          <div class="col-sm-10">
		              <input type="text" class="form-control" id="ketua-sumber" placeholder="Sumber Dana" readonly="true" name="ketua[<?=$i?>][sumber]" value="<?= $arrKetua[$i]['sumber_dana'] ?>">
		          </div>
		      </div>
		      <div class="form-group">
		          <label for="inputAnggaranKetua" class="col-sm-2 control-label">Nilai Anggaran</label>

		          <div class="col-sm-10">
		              <input type="text" class="form-control" id="ketua-nilai" placeholder="Nilai Anggaran" readonly="true" name="ketua[<?=$i?>][nilai]" value="<?= $arrKetua[$i]['nilai'] ?>">
		          </div>
		      </div>

		      <hr>
		  	<?php }
		  	}
		  	 ?>
		    </div>
	    	<!-- /.box-body -->
	  	</div>

	  	<div class="box box-info">
		    <div class="box-header">
		      <h3 class="box-title">Departemen</h3>
		    </div>
		    <div class="box-body">
		    	<?php 
		      	if ($arrDepart) {
		      		for ($i=0; $i < count($arrDepart); $i++) { 
		      	?>
		      <div class="form-group">
		          <label for="inputKodeDepartemen" class="col-sm-2 control-label">Kode Departemen</label>

		          <div class="col-sm-10">
		              <input type="text" class="form-control" id="departemen-kode" placeholder="Kode Departemen" readonly="true" name="departemen[<?=$i?>][kode]" value="<?= $arrDepart[$i]['kode_depart']?>">
		              <input type="hidden" class="form-control" id="departemen-id" placeholder="Id Departemen" readonly="true" name="departemen[<?=$i?>][id]" value="<?= $arrDepart[$i]['id_depart']?>">
		          </div>
		      </div>
		      <div class="form-group">
		          <label for="inputKodeSumberDepartemen" class="col-sm-2 control-label">Kode Sumber Dana</label>

		          <div class="col-sm-10">
		              <input type="text" class="form-control" id="departemen-sumber" placeholder="Sumber Dana" readonly="true" name="departemen[<?=$i?>][sumber]" value="<?= $arrDepart[$i]['sumber_dana']?>">
		          </div>
		      </div>
		      <div class="form-group">
		          <label for="inputAnggaranDepartemen" class="col-sm-2 control-label">Nilai Anggaran</label>

		          <div class="col-sm-10">
		              <input type="text" class="form-control" id="departemen-nilai" placeholder="Password" readonly="true" name="departemen[<?=$i?>][nilai]" value="<?= $arrDepart[$i]['nilai']?>">
		          </div>
		      </div>

		      <hr>
		  	<?php }
		  	}
		  	 ?>
		    </div>
	    	<!-- /.box-body -->
	  	</div>
		<?php if ($arrSeksi) { ?>
	  	<div class="box box-info">
		    <div class="box-header">
		      <h3 class="box-title">Seksi</h3>
		    </div>
		    <div class="box-body">
		    	<?php 
		      	
		      		for ($i=0; $i < count($arrSeksi); $i++) { 
		      	?>
			      <div class="form-group">
			          <label for="inputKodeSeksi" class="col-sm-2 control-label">Kode Seksi</label>

			          <div class="col-sm-10">
			              <input type="text" class="form-control" id="seksi-kode" placeholder="Kode Seksi" readonly="true" name="seksi[<?=$i?>][kode]" value="<?= $arrSeksi[$i]['kode_seksi']?>">
			              <input type="hidden" class="form-control" id="seksi-id" placeholder="Id Seksi" name="seksi[<?=$i?>][id]" value="<?= $arrSeksi[$i]['id_seksi']?>">
			          </div>
			      </div>
			      <div class="form-group">
			          <label for="inputKodeSumberSeksi" class="col-sm-2 control-label">Kode Sumber Dana</label>

			          <div class="col-sm-10">
			              <input type="text" class="form-control" id="seksi-sumber" placeholder="Sumber Dana" readonly="true" name="seksi[<?=$i?>][sumber]" value="<?= $arrSeksi[$i]['sumber_dana']?>">
			          </div>
			      </div>
			      <div class="form-group">
			          <label for="inputAnggaranSeksi" class="col-sm-2 control-label">Nilai Anggaran</label>

			          <div class="col-sm-10">
			              <input type="text" class="form-control" id="seksi-nilai" placeholder="Nilai Anggaran" readonly="true" name="seksi[<?=$i?>][nilai]" value="<?= $arrSeksi[$i]['nilai']?>">
			          </div>
			      </div>
			  	<?php } ?>
		      <hr>
		    </div>
	    	<!-- /.box-body -->
	  	</div>
	  <?php } ?>

	</div>
	<div class="row">
	   <div class="col-xs-12">
	      <div class="box-body" style="margin-left: : 8px; padding-left: 30px">
	         <div class="form-group">
	            <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>
	            <a href="<?= yii\helpers\Url::to(Yii::$app->request->referrer);?>">
	            <button type='button' class='btn btn-danger'>Kembali</button>
	            </a>
	         </div>
	      </div>
	   </div>
	</div>
	<?php ActiveForm::end(); ?>
</div>