<?php

namespace backend\controllers;

use common\models\Activity;
use common\models\ActivityBudgetDepartment;
use common\models\ActivityMainMember;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
use common\models\ChiefBudget;
use common\models\DepartmentBudget;
use common\models\Department;
use common\models\Budget;
use common\models\Model;
use common\models\SecretariatBudget;
use common\models\SectionBudget;
use common\models\User;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
class ActivityDepartmentController extends \yii\web\Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['login', 'error'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout','index','create','update','view','delete','report','kode-tujuan','nilai-anggaran','save-deposit'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $role = Yii::$app->user->identity->role;
        $atasan = Yii::$app->user->identity->department->id_chief;

        $dataProvider = new ActiveDataProvider([
            'query' => Activity::find()
                        ->joinWith('activityBudgetDepartments')
                        ->joinWith('activityBudgetDepartments.departmentBudget')
                        ->joinWith('activityBudgetDepartments.departmentBudget.department')
                        ->where(['role'=>$role])
                        ->andWhere(['department.id_chief'=>$atasan]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Activity();
        $modelsSection = [new ActivitySection()];
        $modelsMember[] = [new ActivitySectionMember];

        if ($model->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post();
            $id_user = Yii::$app->user->identity->id;
            $depId = \common\models\Department::find()->where(['user_id' => $id_user])->one();
            $chiefId = \common\models\Chief::find()->where(['id' => $depId->id_chief])->one();
            $model->role = Yii::$app->user->identity->role;
            $model->finance_status = 0;
            $model->department_status = 1;
            $model->chief_status = 0;
            $model->done = 0;
            $model->date_start = $post['from_date'];
            $model->date_end = $post['to_date'];
            $model->department_code_id = $depId->id;
            $model->chief_code_id = $chiefId->id;

            $data = DepartmentBudget::findOne($post['source_sdm']);
            if ($data->department_budget_value == null) {
              Yii::$app->getSession()->setFlash('danger', 'Jenis SDM / Kode Anggaran Harus Diisi');
              return $this->redirect(Yii::$app->request->referrer);
             } else {
                if ($post['source_value'] > $data->department_budget_value) {
                Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                return $this->redirect(Yii::$app->request->referrer);
                }
             }

            // if ($post['money_budget'] > $post['source_value']) {
            //     Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
            //     return $this->redirect(Yii::$app->request->referrer);
            // }

            if ($post['jenis_sdm_source'] == '7') {
                $data = DepartmentBudget::findOne($post['source_sdm']);
                $data->department_budget_value = $data->department_budget_value - (float) $post['source_value'];
                $data->save();
                $idDepBudget = $data->id;
            }
            // get ActivitySection data from POST
            $modelsSection = Model::createMultiple(ActivitySection::classname());
            Model::loadMultiple($modelsSection, Yii::$app->request->post());

            // get ActivitySectionMember data from POST
            $loadsData = Yii::$app->request->post();
            for ($i = 0; $i < count($modelsSection); $i++) {
                $loadsData['ActivitySectionMember'] = Yii::$app->request->post()['ActivitySectionMember'][$i];
                $modelsMember[$i] = Model::createMultiple(ActivitySectionMember::classname(), [], $loadsData);
                Model::loadMultiple($modelsMember[$i], $loadsData);
            }

            // validate all models - see example online for ajax validation
            // https://github.com/wbraganca/yii2-dynamicform
            $valid = $model->validate();

            // save deposit data
            if ($valid) {
                if ($this->saveDeposit($model, $modelsSection, $modelsMember)) {

                    if ($post) {
                        if ($post['ketua']) {
                            $modelsMain = new ActivityMainMember;
                            $modelsMain->name_committee = "Ketua";
                            $modelsMain->name_member = $post['ketua'];
                            $modelsMain->activity_id = $model->id;
                            // var_dump("test");die;

                            $modelsMain->save();
                        }
                        if ($post['wakil']) {
                            $modelsMain = new ActivityMainMember;
                            $modelsMain->name_committee = "Wakil";
                            $modelsMain->name_member = $post['wakil'];
                            $modelsMain->activity_id = $model->id;
                            // var_dump("test");die;

                            $modelsMain->save();
                        }
                        if ($post['sekretaris']) {
                            $modelsMain = new ActivityMainMember;
                            $modelsMain->name_committee = "Sekretaris";
                            $modelsMain->name_member = $post['sekretaris'];
                            $modelsMain->activity_id = $model->id;
                            // var_dump("test");die;

                            $modelsMain->save();
                        }
                        if ($post['bendahara']) {
                            $modelsMain = new ActivityMainMember;
                            $modelsMain->name_committee = "Bendahara";
                            $modelsMain->name_member = $post['bendahara'];
                            $modelsMain->activity_id = $model->id;
                            // var_dump("test");die;

                            $modelsMain->save();
                        }
                    }

                    $depBudget = new ActivityBudgetDepartment();
                    $depBudget->department_budget_id = $idDepBudget;
                    // $depBudget->budget_value_dp = $post['money_budget'];
                    $depBudget->budget_value_sum = $post['source_value'];
                    $depBudget->activity_id = $model->id;
                    $depBudget->save(false);

                    return $this->redirect('index');
                }
            }
        }

        return $this->render('create', [
            'model' => $model,
            'modelsSection' => (empty($modelsSection)) ? [new ActivitySection] : $modelsSection,
            'modelsMember' => (empty($modelsMember)) ? [new ActivitySectionMember] : $modelsMember,
        ]);
    }

    public function actionUpdate($id)
    {
        $role = Yii::$app->user->identity->role;

        // retrieve existing Deposit data
        $model = Activity::find()->where(['id' => $id])->one();

        $ketua = ActivityMainMember::find()
            ->where(['activity_id' => $id])
            ->andWhere(['name_committee' => "Ketua"])->one();
        $wakil = ActivityMainMember::find()
            ->where(['activity_id' => $id])
            ->andWhere(['name_committee' => "Wakil"])
            ->one();
        $sekretaris = ActivityMainMember::find()
            ->where(['activity_id' => $id])
            ->andWhere(['name_committee' => "Sekretaris"])
            ->one();
        $bendahara = ActivityMainMember::find()
            ->where(['activity_id' => $id])
            ->andWhere(['name_committee' => "Bendahara"])
            ->one();

        if ($role == 7) {
            $budget = ActivityBudgetDepartment::find()->where(['activity_id' => $model->id])->one();
            $awal = ActivityBudgetDepartment::find()->where(['department_budget_id' => $budget])->one();
            $baru = DepartmentBudget::find()->where(['id' => $awal])->one();
            $range = $model->date_start . ' to ' . $model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_sum;
            $oldBudget = $baru->department_budget_value;
        }

        // retrieve existing ActivitySection data
        $oldActivitySectionIds = ActivitySection::find()->select('id')
            ->where(['activity_id' => $id])->asArray()->all();
        $oldActivitySectionIds = ArrayHelper::getColumn($oldActivitySectionIds, 'id');
        $modelsSection = ActivitySection::findAll(['id' => $oldActivitySectionIds]);
        $modelsSection = (empty($modelsSection)) ? [new ActivitySection] : $modelsSection;

        // retrieve existing Loads data
        $oldLoadIds = [];
        foreach ($modelsSection as $i => $modelSection) {
            $oldLoads = ActivitySectionMember::findAll(['section_activity_id' => $modelSection->id]);
            $modelsMember[$i] = $oldLoads;
            $oldLoadIds = array_merge($oldLoadIds, ArrayHelper::getColumn($oldLoads, 'id'));
            $modelsMember[$i] = (empty($modelsMember[$i])) ? [new ActivitySectionMember] : $modelsMember[$i];
        }

        // handle POST
        if ($model->load(Yii::$app->request->post())) {

            $post = Yii::$app->request->post();
            $model->role = Yii::$app->user->identity->role;
            $model->finance_status = 0;
            $model->department_status = 1;
            $model->chief_status = 0;
            $model->done = 0;
            $model->date_start = $post['from_date'];
            $model->date_end = $post['to_date'];

            // get ActivitySection data from POST
            $modelsSection = Model::createMultiple(ActivitySection::classname(), $modelsSection);
            Model::loadMultiple($modelsSection, Yii::$app->request->post());
            $newActivitySectionIds = ArrayHelper::getColumn($modelsSection, 'id');

            // get ActivitySectionMember data from POST
            $newLoadIds = [];
            $loadsData = Yii::$app->request->post();
            for ($i = 0; $i < count($modelsSection); $i++) {
                $loadsData['ActivitySectionMember'] = Yii::$app->request->post()['ActivitySectionMember'][$i];
                $modelsMember[$i] = Model::createMultiple(ActivitySectionMember::classname(), $modelsMember[$i], $loadsData);
                Model::loadMultiple($modelsMember[$i], $loadsData);
                $newLoadIds = array_merge($newLoadIds, ArrayHelper::getColumn($loadsData['ActivitySectionMember'], 'id'));
            }

            // delete removed data
            $delLoadIds = array_diff($oldLoadIds, $newLoadIds);
            if (!empty($delLoadIds)) {
                ActivitySectionMember::deleteAll(['id' => $delLoadIds]);
            }

            $delActivitySectionIds = array_diff($oldActivitySectionIds, $newActivitySectionIds);
            if (!empty($delActivitySectionIds)) {
                ActivitySection::deleteAll(['id' => $delActivitySectionIds]);
            }

            // validate all models
            $valid = $model->validate();

            // save deposit data
            if ($valid) {
                if ($this->saveDeposit($model, $modelsSection, $modelsMember) && $budget->load(Yii::$app->request->post())) {

                    if ($role == 7) {

                        // $dp = $budget->budget_value_dp;
                        $total = $budget->budget_value_sum;
                        $modal = $baru->department_budget_value;

                        //nilai anggaran dp lebih kecil dari anggaran saat ini
                        if ($total <= $modal) {
                            $dpBaru = $oldDP - $total;
                            $oldBudgetBaru = $modal + $dpBaru;
                            if ($oldBudgetBaru <= 0) {
                                var_dump($oldBudgetBaru);die();
                                Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                                return $this->redirect(Yii::$app->request->referrer);
                            }
                        }

                        //nilai anggaran dp lebih besar dari anggaran saat ini
                        if ($total >= $modal) {
                            $dpBaru = $total - $oldDP;
                            $oldBudgetBaru = $modal - $dpBaru;
                            if ($oldBudgetBaru <= 0) {
                                var_dump($oldBudgetBaru);die();
                                Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                                return $this->redirect(Yii::$app->request->referrer);
                            }
                        }

                        // $budget->budget_value_dp = $budget->budget_value_dp;
                        $budget->budget_value_sum = $budget->budget_value_sum;
                        $budget->save(false);

                        $baru->department_budget_value = $oldBudgetBaru;
                        $baru->save(false);

                    }
                    if ($post) {
                        if ($post['ketua']) {
                            $modelsMain = ActivityMainMember::find()->where(['activity_id' => $id])->andWhere(['name_committee' => 'Ketua'])->one();
                            $modelsMain->name_member = $post['ketua'];
                            $modelsMain->save();
                        }
                        if ($post['wakil']) {
                            $modelsMain = ActivityMainMember::find()->where(['activity_id' => $id])->andWhere(['name_committee' => 'Wakil'])->one();
                            $modelsMain->name_member = $post['wakil'];
                            $modelsMain->save();
                        }
                        if ($post['sekretaris']) {
                            $modelsMain = ActivityMainMember::find()->where(['activity_id' => $id])->andWhere(['name_committee' => 'Sekretaris'])->one();
                            $modelsMain->name_member = $post['sekretaris'];
                            $modelsMain->save();
                        }
                        if ($post['bendahara']) {
                            $modelsMain = ActivityMainMember::find()->where(['activity_id' => $id])->andWhere(['name_committee' => 'Bendahara'])->one();
                            $modelsMain->name_member = $post['bendahara'];
                            $modelsMain->save();
                        }
                    }

                    return $this->redirect('index');
                }
            }
        }

        // show VIEW
        return $this->render('_form-update', [
            'model' => $model,
            'budget' => $budget,
            'baru' => $baru,
            'ketua' => $ketua,
            'wakil' => $wakil,
            'bendahara' => $bendahara,
            'sekretaris' => $sekretaris,
            'modelsSection' => $modelsSection,
            'modelsMember' => $modelsMember,
            'range' => $range,
            'range_start' => $range_start,
            'range_end' => $range_end,
        ]);
    }

   /**
     * Displays a single Activity model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
      $role = Yii::$app->user->identity->role;

      // retrieve existing Deposit data
      $model = Activity::find()->where(['id' => $id])->one();

      $ketua = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Ketua"])->one();
      $wakil = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Wakil"])
          ->one();
      $sekretaris = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Sekretaris"])
          ->one();
      $bendahara = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Bendahara"])
          ->one();

      if ($role == 7) {
          $budget = ActivityBudgetDepartment::find()->where(['activity_id' => $model->id])->one();
          $awal = ActivityBudgetDepartment::find()->where(['department_budget_id' => $budget])->one();
          $baru = DepartmentBudget::find()->where(['id' => $awal])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->department_budget_value;
      }

      // retrieve existing ActivitySection data
      $oldActivitySectionIds = ActivitySection::find()->select('id')
          ->where(['activity_id' => $id])->asArray()->all();
      $oldActivitySectionIds = ArrayHelper::getColumn($oldActivitySectionIds, 'id');
      $modelsSection = ActivitySection::findAll(['id' => $oldActivitySectionIds]);
      $modelsSection = (empty($modelsSection)) ? [new ActivitySection] : $modelsSection;

      // retrieve existing Loads data
      $oldLoadIds = [];
      foreach ($modelsSection as $i => $modelSection) {
          $oldLoads = ActivitySectionMember::findAll(['section_activity_id' => $modelSection->id]);
          $modelsMember[$i] = $oldLoads;
          $oldLoadIds = array_merge($oldLoadIds, ArrayHelper::getColumn($oldLoads, 'id'));
          $modelsMember[$i] = (empty($modelsMember[$i])) ? [new ActivitySectionMember] : $modelsMember[$i];
      }


        return $this->render('view', [
            'model' => $model,
            'budget' => $budget,
            'baru' => $baru,
            'ketua' => $ketua,
            'wakil' => $wakil,
            'sekretaris' => $sekretaris,
            'bendahara' => $bendahara,
            'modelsSection' => $modelsSection,
            'modelsMember' => $modelsMember,
            'range' => $range,
            'range_start' => $range_start,
            'range_end' => $range_end,
        ]);
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionReport($id) {

        $role = Yii::$app->user->identity->role;

          $model = Activity::find()->where(['id'=>$id])->one();
          $budget = ActivityBudgetDepartment::find()->where(['activity_id'=>$model])->one();
          $awal = ActivityBudgetDepartment::find()->where(['Department_budget_id'=>$budget])->one();
          $baru = DepartmentBudget::find()->where(['id'=>$awal])->one();
          $department = Department::find()->where(['id'=>$model->department_code_id])->one();
          $section = ActivitySection::find()->where(['activity_id'=>$model->id])->all();
          $mainMember = ActivityMainMember::find()->where(['activity_id'=>$model->id])->one();
          $ketua = ActivityMainMember::find()->where(['name_committee'=>'Ketua'])->andWhere(['activity_id'=>$mainMember])->one();
          $wakil = ActivityMainMember::find()->where(['name_committee'=>'Wakil'])->andWhere(['activity_id'=>$mainMember])->one();
          $sekretaris = ActivityMainMember::find()->where(['name_committee'=>'Sekretaris'])->andWhere(['activity_id'=>$mainMember])->one();
          $bendahara = ActivityMainMember::find()->where(['name_committee'=>'Bendahara'])->andWhere(['activity_id'=>$mainMember])->one();
        $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'department'=>$department,
            'section'=>$section,
            'mainMember'=>$mainMember,
            'ketua'=>$ketua,
            'wakil'=>$wakil,
            'sekretaris'=>$sekretaris,
            'bendahara'=>$bendahara,
        ]);

        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE,
            // A4 paper format
            'format' => Pdf::FORMAT_A4,
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT,
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER,
            // your html content input
            'content' => $content,
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
             // call mPDF methods on the fly
            'methods' => [
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

    // return the pdf output as per the destination setting
    return $pdf->render();
    }

    ///////////////////////////////////////////////////////////////

    public function actionKodeTujuan($id)
    {
        if ($id == '4') {
            $data = SecretariatBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='" . $datas->id . "'>" . $datas->secretariat_budget_code . "</option>";
                }
                $budgetSekre = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id' => $datas])->one();
            }
        } elseif ($id == '6') {
            $data = ChiefBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='" . $datas->id . "'>" . $datas->chief_budget_code . "</option>";
                }
            }
        } elseif ($id == '7') {
            $data = DepartmentBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='" . $datas->id . "'>" . $datas->department_budget_code . "</option>";
                }
            }
        } elseif ($id == '8') {
            $data = SectionBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='" . $datas->id . "'>" . $datas->section_budget_code . "</option>";
                }
            }
        } else {
            echo "<option value=0'> Pilih Kode Anggaran </option>";
        }
    }

    public function actionNilaiAnggaran()
    {
        $post = Yii::$app->request->post();
        if ($post['tipe'] == '4') {
            $data = SecretariatBudget::findOne($post['kode']);
            if ($data) {
                $datas['message'] = "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            Rp." . $data->secretariat_budget_value . "
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max'] = $data->secretariat_budget_value;
            } else {
                $datas['message'] = "
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
                $datas['max'] = 0;
            }
        } elseif ($post['tipe'] == '6') {
            $data = ChiefBudget::findOne($post['kode']);
            if ($data) {
                $datas['message'] = "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            " . $data->chief_budget_value . "
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max'] = $data->chief_budget_value;
            } else {
                $datas['message'] = "
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
                $datas['max'] = 0;
            }
        } elseif ($post['tipe'] == '7') {
            $data = DepartmentBudget::findOne($post['kode']);
            if ($data) {
                $datas['message'] = "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            " . $data->department_budget_value . "
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max'] = $data->department_budget_value;
            } else {
                $datas['message'] = "
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
                $datas['max'] = 0;
            }
        } elseif ($post['tipe'] == '8') {
            $data = SectionBudget::findOne($post['kode']);
            if ($data) {
                $datas['message'] = "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            Rp." . $data->section_budget_value . "
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max'] = $data->section_budget_value;
            } else {
                $datas['message'] = "
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
                $datas['max'] = 0;
            }
        } else {
            $datas['message'] = "
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
            $datas['max'] = 0;
        }
        echo json_encode($datas);
    }

    protected function saveDeposit($model, $modelsSection, $modelsMember)
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($go = $model->save(false)) {

                // loop through each payment
                foreach ($modelsSection as $i => $modelSection) {
                    // save the payment record
                    $modelSection->activity_id = $model->id;
                    if ($go = $modelSection->save(false)) {
                        // loop through each load
                        foreach ($modelsMember[$i] as $ix => $modelMember) {
                            // save the load record
                            $modelMember->activity_id = $model->id;
                            $modelMember->section_activity_id = $modelSection->id;
                            if (!($go = $modelMember->save(false))) {
                                $transaction->rollBack();
                                break;
                            }

                        }
                    }
                }
            }
            if ($go) {
                $transaction->commit();
            }
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return $go;
    }

    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
