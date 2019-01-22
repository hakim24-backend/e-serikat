<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\ChiefBudget;
use common\models\DepartmentBudget;
use common\models\SecretariatBudget;
use common\models\SectionBudget;
use common\models\Chief;
use common\models\Department;
use common\models\Secretariat;
use common\models\Section;
use common\models\Budget;
use yii\data\ActiveDataProvider;
use yii\web\UploadedFile;

/**
 * Transfer controller
 */
class TransferController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays index.
     *
     * @return string
     */
    public function actionIndex()
    {
        $budgetSecretariat = new ActiveDataProvider([
            'query' => SecretariatBudget::find(),
        ]);
        $budgetChief = new ActiveDataProvider([
            'query' => ChiefBudget::find(),
        ]);
        $budgetDepartment = new ActiveDataProvider([
            'query' => DepartmentBudget::find(),
        ]);
        $budgetSection = new ActiveDataProvider([
            'query' => SectionBudget::find(),
        ]);

        return $this->render('index', [
            'budgetSecretariat' => $budgetSecretariat,
            'budgetChief' => $budgetChief,
            'budgetDepartment'=> $budgetDepartment,
            'budgetSection'=> $budgetSection
        ]);
    }

    public function actionCreate()
    {

       return $this->render('_form_import');
    }

    public function actionResult()
    {
        ini_set("precision", "15");

        if(Yii::$app->request->post())
        {
            $file = UploadedFile::getInstanceByName('file');
            $uploadPath = Yii::getAlias('@backend')."/web/template";
            $acak = substr( md5(time()) , 0, 10);
            $fname = $uploadPath."/template_".$file->baseName ."_". $acak.".".$file->extension;
            $file->saveAs($fname);

            $arrKetua = array();
            $arrDepart = array();
            $arrSeksi = array();
            $arrSekretariat = array();

            try {
                $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($fname);
                $worksheet = $spreadsheet->getSheet(0);
                $highestRow = $worksheet->getHighestRow();
                $highestColumn = $worksheet->getHighestColumn();
                $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
                
                $dataArr = array();
                
                for ($row = 2; $row <= $highestRow; ++$row) {
                   
                    for ($col = 0; $col <= $highestColumnIndex; ++$col) {
                        $value = $worksheet->getCellByColumnAndRow($col + 1, $row)->getValue();
                        $dataArr[$row][$col] = $value;
                    }
                    
                }

                $success = false;

                $no = 0;
                $no_ketua = 0;
                $no_depart = 0;
                $no_seksi = 0;
                $no_sekretariat = 0;
                foreach($dataArr as $key => $val) {

                    $sumber_dana    = trim($this->cek_null($val[0]));
                    $nilai_anggaran = trim($this->cek_null($val[5]));
                    $kode_ketua     = trim($this->cek_null($val[1]));
                    $kode_depart     = trim($this->cek_null($val[2]));
                    $kode_seksi     = trim($this->cek_null($val[3]));
                    $kode_sekretariat     = trim($this->cek_null($val[4]));
                    if($sumber_dana != "" && $sumber_dana != "")
                    {
                        $sumber_dana_find = Budget::find()->where(['budget_code'=>$sumber_dana])->one();
                        if ($sumber_dana_find) {
                            if($kode_ketua != "" && $kode_depart=="" && $kode_seksi=="" && $kode_sekretariat=="") {
                                $kode_ketua_find = Chief::find()->where(['chief_code'=>$kode_ketua])->one();
                                if ($kode_ketua_find) {
                                    $arrKetua[$no_ketua]['nilai'] = $nilai_anggaran;
                                    $arrKetua[$no_ketua]['sumber_dana'] = $sumber_dana;
                                    $arrKetua[$no_ketua]['kode_ketua'] = $kode_ketua;
                                    $arrKetua[$no_ketua]['id_ketua'] = $kode_ketua_find->id;
                                    $no_ketua++;
                                }
                            }else if($kode_ketua != "" && $kode_depart!="" && $kode_seksi=="" && $kode_sekretariat=="") {
                                $kode_depart_find = Department::find()->where(['depart_code'=>$kode_depart])->one();
                                if ($kode_depart_find) {
                                    $arrDepart[$no_depart]['nilai'] = $nilai_anggaran;
                                    $arrDepart[$no_depart]['sumber_dana'] = $sumber_dana;
                                    $arrDepart[$no_depart]['kode_depart'] = $kode_depart;
                                    $arrDepart[$no_depart]['id_depart'] = $kode_depart_find->id;
                                    $no_depart++;
                                }
                            }else if($kode_ketua != "" && $kode_depart!="" && $kode_seksi!="" && $kode_sekretariat=="") {
                                $kode_seksi_find = Section::find()->where(['section_code'=>$kode_seksi])->one();
                                if ($kode_seksi_find) {
                                    $arrSeksi[$no_seksi]['nilai'] = $nilai_anggaran;
                                    $arrSeksi[$no_seksi]['sumber_dana'] = $sumber_dana;
                                    $arrSeksi[$no_seksi]['kode_seksi'] = $kode_seksi;
                                    $arrSeksi[$no_seksi]['id_seksi'] = $kode_seksi_find->id;
                                    $no_seksi++;
                                }
                            }else if($kode_ketua == "" && $kode_depart=="" && $kode_seksi=="" && $kode_sekretariat!="") {
                                $kode_sekretariat_find = Secretariat::find()->where(['secretariat_code'=>$kode_sekretariat])->one();
                                if ($kode_sekretariat_find) {
                                    $arrSekretariat[$no_sekretariat]['nilai'] = $nilai_anggaran;
                                    $arrSekretariat[$no_sekretariat]['sumber_dana'] = $sumber_dana;
                                    $arrSekretariat[$no_sekretariat]['kode_sekretariat'] = $kode_sekretariat;
                                    $arrSekretariat[$no_sekretariat]['id_sekretariat'] = $kode_sekretariat_find->id;
                                    $no_sekretariat++;
                                }
                            }
                        }
                        
                    }

                }
            }catch (Exception $e) { // end try
                unlink($fname);
                echo "Error loading file: ".$e->getMessage()."<br />\n";
                die();
            }

            // hapus file excel yang barusan di upload
            unlink($fname);
            return $this->render("result",[
                'arrKetua'=>$arrKetua,
                'arrDepart' => $arrDepart,
                'arrSeksi' => $arrSeksi,
                'arrSekretariat' => $arrSekretariat,
            ]);
            
        }
    }

    public function actionSave()
    {
        $post = Yii::$app->request->post();

        if (isset($post['sekretariat'])) {
            foreach ($post['sekretariat'] as $key => $value) {
                $sumber_dana_find = Budget::find()->where(['budget_code'=>$value['sumber']])->one();
                $sekretariat = new SecretariatBudget();
                $sekretariat->secretariat_budget_id = $sumber_dana_find->id;
                $sekretariat->secretariat_budget_value = $value['nilai'];
                $sekretariat->secretariat_id = $value['id'];

                $kodeAnggaran = 'SEKRETARIAT'.'-'.$sumber_dana_find->budget_code.'-';

                $listKodeAnggaran = SecretariatBudget::find()
                    ->where(['LIKE', 'secretariat_budget_code', $kodeAnggaran])
                    ->orderBy(['secretariat_budget_code' => SORT_DESC])
                    ->limit(1)
                    ->one();
                if ($listKodeAnggaran == null) {
                    $counter = '001';
                }else{
                    $counter = explode('-', $listKodeAnggaran['secretariat_budget_code'])[3];
                    $counter = str_pad($counter+1, 3, '0', STR_PAD_LEFT);
                }
                $sekretariat->secretariat_budget_code = $kodeAnggaran.$counter;
                $sekretariat->save();

            }

        }

        if (isset($post['ketua'])) {
            foreach ($post['ketua'] as $key => $value) {
                $sumber_dana_find = Budget::find()->where(['budget_code'=>$value['sumber']])->one();
                $ketua = new ChiefBudget();
                $ketua->chief_budget_id = $sumber_dana_find->id;
                $ketua->chief_budget_value = $value['nilai'];
                $ketua->chief_id = $value['id'];

                $kodeAnggaran = 'KETUA'.'-'.$sumber_dana_find->budget_code.'-';

                $listKodeAnggaran = ChiefBudget::find()
                    ->where(['LIKE', 'chief_budget_code', $kodeAnggaran])
                    ->orderBy(['chief_budget_code' => SORT_DESC])
                    ->limit(1)
                    ->one();
                if ($listKodeAnggaran == null) {
                    $counter = '001';
                }else{
                    $counter = explode('-', $listKodeAnggaran['chief_budget_code'])[3];
                    $counter = str_pad($counter+1, 3, '0', STR_PAD_LEFT);
                }
                $ketua->chief_budget_code = $kodeAnggaran.$counter;
                $ketua->save();

            }
        }

        if (isset($post['departemen'])) {
            foreach ($post['departemen'] as $key => $value) {
                $sumber_dana_find = Budget::find()->where(['budget_code'=>$value['sumber']])->one();
                
                $departemen = new DepartmentBudget();
                $departemen->department_budget_id = $sumber_dana_find->id;
                $departemen->department_budget_value = $value['nilai'];
                $departemen->department_id = $value['id'];

                $kodeAnggaran = 'DEPARTEMEN'.'-'.$sumber_dana_find->budget_code.'-';

                $listKodeAnggaran = DepartmentBudget::find()
                    ->where(['LIKE', 'department_budget_code', $kodeAnggaran])
                    ->orderBy(['department_budget_code' => SORT_DESC])
                    ->limit(1)
                    ->one();
                if ($listKodeAnggaran == null) {
                    $counter = '001';
                }else{
                    $counter = explode('-', $listKodeAnggaran['department_budget_code'])[3];
                    $counter = str_pad($counter+1, 3, '0', STR_PAD_LEFT);
                }
                $departemen->department_budget_code = $kodeAnggaran.$counter;
                $departemen->save();

            }
        }
        if (isset($post['seksi'])) {
            foreach ($post['seksi'] as $key => $value) {
                $sumber_dana_find = Budget::find()->where(['budget_code'=>$value['sumber']])->one();

                $seksi = new SectionBudget();
                $seksi->section_budget_id = $sumber_dana_find->id;
                $seksi->section_budget_value = $value['nilai'];
                $seksi->section_id = $value['id'];
                

                $kodeAnggaran = 'SEKSI'.'-'.$sumber_dana_find->budget_code.'-';

                $listKodeAnggaran = SectionBudget::find()
                    ->where(['LIKE', 'section_budget_code', $kodeAnggaran])
                    ->orderBy(['section_budget_code' => SORT_DESC])
                    ->limit(1)
                    ->one();
                if ($listKodeAnggaran == null) {
                    $counter = '001';
                }else{
                    $counter = explode('-', $listKodeAnggaran['section_budget_code'])[3];
                    $counter = str_pad($counter+1, 3, '0', STR_PAD_LEFT);
                }
                $seksi->section_budget_code = $kodeAnggaran.$counter;
                $seksi->save();

            }
        }

        Yii::$app->getSession()->setFlash('success', 'Berhasil!');
        return $this->redirect(['index']);
    }

    public function actionUpdateSekretariat($id)
    {
        $model = SecretariatBudget::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Berhasil!');
            return $this->redirect(['index']);
        }
        return $this->render('update-form-sekretariat', [
            'model' => $model,
        ]);
    }

    public function actionUpdateKetua($id)
    {
        $model = ChiefBudget::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Berhasil!');
            return $this->redirect(['index']);
        }
        return $this->render('update-form-ketua', [
            'model' => $model,
        ]);
        
    }

    public function actionUpdateDepartemen($id)
    {
        $model = DepartmentBudget::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Berhasil!');
            return $this->redirect(['index']);
        }
        return $this->render('update-form-departemen', [
            'model' => $model,
        ]);
    }
    
    public function actionUpdateSeksi($id)
    {
        $model = SectionBudget::findOne($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Berhasil!');
            return $this->redirect(['index']);
        }
        return $this->render('update-form-seksi', [
            'model' => $model,
        ]);
    }

    private function cek_null($var)
    {
        $re = is_null($var) ? "" : $var;
        return $re;
    }

}
