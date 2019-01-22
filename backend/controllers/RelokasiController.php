<?php

namespace backend\controllers;

use Yii;
use common\models\TransferRecord;
use common\models\SecretariatBudget;
use common\models\ChiefBudget;
use common\models\DepartmentBudget;
use common\models\SectionBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

class RelokasiController extends \yii\web\Controller
{
    public function actionCreate()
    {
    	if (Yii::$app->request->post()) {
    		$post = Yii::$app->request->post();
            if ($post['jenis_sdm_source']=='4') {
                $data = SecretariatBudget::findOne($post['source_sdm']);
                $data->secretariat_budget_value=$data->secretariat_budget_value-(float)$post['source_value'];
                $data->save();
                $kode_asal = $data->secretariat_budget_code;
            }elseif ($post['jenis_sdm_source']=='6') {
                $data = ChiefBudget::findOne($post['source_sdm']);
                $data->chief_budget_value=$data->chief_budget_value-(float)$post['source_value'];
                $data->save();
                $kode_asal = $data->chief_budget_code;

            }elseif ($post['jenis_sdm_source']=='7') {
                $data = DepartmentBudget::findOne($post['source_sdm']);
                $data->department_budget_value=$data->department_budget_value-(float)$post['source_value'];
                $data->save();
                $kode_asal = $data->department_budget_code;
            }elseif ($post['jenis_sdm_source']=='8') {
                $data = SectionBudget::findOne($post['source_sdm']);
                $data->section_budget_value=$data->section_budget_value-(float)$post['source_value'];
                $data->save();
                $kode_asal = $data->section_budget_code;
            }
            if ($post['jenis_sdm_dest']=='4') {
                $data = SecretariatBudget::findOne($post['dest_sdm']);
                $data->secretariat_budget_value=$data->secretariat_budget_value+(float)$post['source_value'];
                $data->save();
                $kode_tujuan = $data->secretariat_budget_code;
            }elseif ($post['jenis_sdm_dest']=='6') {
                $data = ChiefBudget::findOne($post['dest_sdm']);
                $data->chief_budget_value=$data->chief_budget_value+(float)$post['source_value'];
                $data->save();
                $kode_tujuan = $data->chief_budget_code;
            }elseif ($post['jenis_sdm_dest']=='7') {
                $data = DepartmentBudget::findOne($post['dest_sdm']);
                $data->department_budget_value=$data->department_budget_value+(float)$post['source_value'];
                $data->save();
                $kode_tujuan = $data->department_budget_code;
            }elseif ($post['jenis_sdm_dest']=='8') {
                $data = SectionBudget::findOne($post['dest_sdm']);
                $data->section_budget_value=$data->section_budget_value+(float)$post['source_value'];
                $data->save();
                $kode_tujuan = $data->section_budget_code;
            }

            $relokasi = new TransferRecord();
            $relokasi->code_source = $kode_asal;
            $relokasi->code_dest = $kode_tujuan;
            $relokasi->value = (float)$post['source_value'];
            $relokasi->save();

            Yii::$app->getSession()->setFlash('success', 'Berhasil!');
            return $this->redirect(['index']);
    	}
        return $this->render('_form');
    }

    public function actionIndex()
    {
       $dataProvider = new ActiveDataProvider([
            'query' => TransferRecord::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionKodeTujuan($id)
    {
    	if ($id=='4') {
    		$data = SecretariatBudget::find()->all();
    		echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->secretariat_budget_code."</option>";
                }
            }
    	}elseif ($id=='6') {
    		$data = ChiefBudget::find()->all();
    		echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->chief_budget_code."</option>";
                }
            }
    	}elseif ($id=='7') {
    		$data = DepartmentBudget::find()->all();
    		echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->department_budget_code."</option>";
                }
            }
    	}elseif ($id=='8') {
    		$data = SectionBudget::find()->all();
    		echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->section_budget_code."</option>";
                }
            }
    	}else{
    		echo "<option value=0'> Pilih Kode Anggaran </option>";
    	}
    }

    public function actionNilaiAnggaran(){
    	$post = Yii::$app->request->post();
        // var_dump($post);die;
    	if ($post['tipe']=='4') {
    		$data = SecretariatBudget::findOne($post['kode']);
    		if ($data) {
    			$datas['message']= "
    			 <div class='col-sm-12'>
		            <div class='form-group'>
		                <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
		                <div class='col-sm-8'>
		                    ".$data->secretariat_budget_value."
		                </div>
		            </div>
		        </div>
		        <br>
		        <br>
    			";
    			$datas['max']=$data->secretariat_budget_value;
    		}else{
    			$datas['message']= "
    			 <div class='col-sm-12'>
		            <div class='form-group'>
		                <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
		                <div class='col-sm-8'>
		                    0
		                </div>
		            </div>
		        </div>
		        <br>
		        <br>
    			";
    			$datas['max']=0;
    		}
    	}elseif ($post['tipe']=='6') {
    		$data = ChiefBudget::findOne($post['kode']);
    		if ($data) {
    			$datas['message']= "
    			 <div class='col-sm-12'>
		            <div class='form-group'>
		                <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
		                <div class='col-sm-8'>
		                    ".$data->chief_budget_value."
		                </div>
		            </div>
		        </div>
		        <br>
		        <br>
    			";
    			$datas['max']=$data->chief_budget_value;
    		}else{
    			$datas['message']= "
    			 <div class='col-sm-12'>
		            <div class='form-group'>
		                <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
		                <div class='col-sm-8'>
		                    0
		                </div>
		            </div>
		        </div>
		        <br>
		        <br>
    			";
    			$datas['max']=0;
    		}
    	}elseif ($post['tipe']=='7') {
    		$data = DepartmentBudget::findOne($post['kode']);
    		if ($data) {
    			$datas['message']= "
    			 <div class='col-sm-12'>
		            <div class='form-group'>
		                <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
		                <div class='col-sm-8'>
		                    ".$data->department_budget_value."
		                </div>
		            </div>
		        </div>
		        <br>
		        <br>
    			";
    			$datas['max']=$data->department_budget_value;
    		}else{
    			$datas['message']= "
    			 <div class='col-sm-12'>
		            <div class='form-group'>
		                <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
		                <div class='col-sm-8'>
		                    0
		                </div>
		            </div>
		        </div>
		        <br>
		        <br>
    			";
    			$datas['max']=0;
    		}
    	}elseif ($post['tipe']=='8') {
    		$data = SectionBudget::findOne($post['kode']);
    		if ($data) {
    			$datas['message']= "
    			 <div class='col-sm-12'>
		            <div class='form-group'>
		                <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
		                <div class='col-sm-8'>
		                    ".$data->section_budget_value."
		                </div>
		            </div>
		        </div>
		        <br>
		        <br>
    			";
    			$datas['max']=$data->section_budget_value;
    		}else{
    			$datas['message']= "
    			 <div class='col-sm-12'>
		            <div class='form-group'>
		                <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
		                <div class='col-sm-8'>
		                    0
		                </div>
		            </div>
		        </div>
		        <br>
		        <br>
    			";
    			$datas['max']=0;
    		}
    	}else{
    		$datas['message']= "
			 <div class='col-sm-12'>
	            <div class='form-group'>
	                <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
	                <div class='col-sm-8'>
	                    0
	                </div>
	            </div>
	        </div>
	        <br>
	        <br>
			";
			$datas['max']=0;
    	}
    	echo json_encode($datas);

    }

}
