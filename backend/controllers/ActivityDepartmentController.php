<?php

namespace backend\controllers;

use common\models\Activity;
use common\models\ActivityBudgetDepartment;
use common\models\ActivityMainMember;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
use common\models\ChiefBudget;
use common\models\DepartmentBudget;
use common\models\Model;
use common\models\SecretariatBudget;
use common\models\SectionBudget;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;

class ActivityDepartmentController extends \yii\web\Controller
{
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

    public function actionIndex()
    {
        $role = Yii::$app->user->identity->role;
        $dataProvider = new ActiveDataProvider([
            'query' => Activity::find()->where(['role' => $role]),
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
            $model->department_status = 0;
            $model->chief_status = 0;
            $model->done = 0;
            $model->date_start = $post['from_date'];
            $model->date_end = $post['to_date'];
            $model->department_code_id = $depId->id;
            $model->chief_code_id = $chiefId->id;
            $data = DepartmentBudget::findOne($post['source_sdm']);

            if ($post['money_budget'] > $post['source_value']) {
                Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                return $this->redirect(Yii::$app->request->referrer);
            }

            if ($post['source_value'] > $data->department_budget_value) {
                Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                return $this->redirect(Yii::$app->request->referrer);
            }
            if ($post['jenis_sdm_source'] == '7') {
                $data = DepartmentBudget::findOne($post['source_sdm']);
                $data->department_budget_value = $data->department_budget_value - (float) $post['money_budget'];
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
                    $depBudget->budget_value_dp = $post['money_budget'];
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

    }

    public function actionView($id)
    {

    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
