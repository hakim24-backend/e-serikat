<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\helpers\Url;
use kartik\file\FileInput;
use yii\widgets\ActiveForm;
use yii\web\Session;
use yii\base\view;

$request = Yii::$app->request;
$this->title = 'Import Excel';

$this->params['breadcrumbs'][] = ['label' => 'Data', 'url' => ['transfer/']];
$this->params['breadcrumbs'][] = $this->title;

?>
<div class="row">
    
	<div class='col-md-12'>

		<div class='box box-info'>	 
	    
	        <div class='box-body'>
			  <?= Yii::$app->session->getFlash('message') ?>

			  <!--- mulai form!-->
            
    	        	<?php $form = ActiveForm::begin([
    	        					'options' => [
    	        						'enctype' => 'multipart/form-data', 
    	        						'class' => 'form-horizontal'
    	        					],
    	        					'action' => ['transfer/result']
    	        			]); ?>
						<div class="box-body">
							<div class="form-group">
						            <label class="col-sm-2 control-label">File Excel</label>
						            <div class="col-sm-8">
						               
						                <?= 
						                	FileInput::widget([
											    'name' => 'file',

											    'options' => [
											    	'accept' => "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel",
									                'multiple' => false,
									                'required' => 'required',
									                'allowedFileExtensions'=>['xls', 'xlsx'],
									                ],
									            'pluginOptions' => [
									                'showPreview' => false,
									                'showCaption' => true,
									                'showRemove' => true,
									                'showUpload' => false
									                ],
											]);
									    ?>
	                          			
						            </div>
									<div class="col-sm-2">
	                          			<a href="<?= \Yii::$app->urlManager->createUrl('template/template.xlsx');?>">Unduh Template</a>
	                          		</div>
						    </div>
						   
		                
							<div class="form-group">
								<label class="col-sm-2 control-label"></label>
		                        <div class="col-sm-2">
		                          <input type="submit" class="btn btn-success" value="Unggah">
		                          <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
		                        </div>
								<div class="col-sm-4">
									
		                        </div>
				            </div>	
				        </div>
					<?php ActiveForm::end(); ?>

            	<!--- end form!-->	
	        </div>
		</div> 			
	</div>

</div>