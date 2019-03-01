<?php

namespace backend\controllers;

use common\models\ActivityDaily;
use common\models\ActivityDailyBudgetDepart;
use common\models\ActivityBudgetDepartment;
use common\models\ActivityDailyReject;
use common\models\DepartmentBudget;
use common\models\Department;
use common\models\Budget;
use common\models\User;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

class ActivityDailyDepartmentController extends \yii\web\Controller
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
                        'actions' => ['logout', 'index','create','update','view','report'],
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
            'query' => ActivityDaily::find()
                        ->joinWith('activityDailyBudgetDeparts')
                        ->joinWith('activityDailyBudgetDeparts.departmentBudget')
                        ->joinWith('activityDailyBudgetDeparts.departmentBudget.department')
                        ->where(['role'=>$role])
                        ->andWhere(['department.id_chief'=>$atasan])
                        ->andWhere(['activity_daily.done'=>0]),

        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        if (Yii::$app->request->post()) {

            $role = Yii::$app->user->identity->roleName();
            $tahun = date('Y');
            $bulan = date('m');
            $tanggal = date('d');

            if ($role == "Departemen") {
                $idDep = 0;
                $post = Yii::$app->request->post();
                $data = DepartmentBudget::findOne($post['source_sdm']);

                if ($data == null) {
                    Yii::$app->getSession()->setFlash('danger', 'Jenis SDM / Kode Anggaran Harus Diisi');
                    return $this->redirect(Yii::$app->request->referrer);
                } else {
                    if ($post['source_value'] > $data->department_budget_value ) {
                    Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                    return $this->redirect(Yii::$app->request->referrer);
                    }
                }

                if ($post['jenis_sdm_source'] == '7') {
                    $data = DepartmentBudget::findOne($post['source_sdm']);

                    $data->department_budget_value = $data->department_budget_value - (float) $post['source_value'];
                    $data->save();

                    $idDep = $data->id;
                }
                $id_user = Yii::$app->user->identity->id;
                $depId = \common\models\Department::find()->where(['user_id' => $id_user])->one();
                $chiefId = \common\models\Chief::find()->where(['id' => $depId->id_chief])->one();

                $daily = new ActivityDaily();
                $daily->finance_status = 0;
                $daily->department_status = 1;
                $daily->chief_status = 0;
                $daily->title = $post['judul'];
                $daily->description = $post['description'];
                $daily->role = 7;
                $daily->date_start = $post['from_date'];
                $daily->date_end = $post['to_date'];
                $daily->done = 0;
                $daily->department_code_id = $depId->id;
                $daily->chief_code_id = $chiefId->id;

                $daily->save(false);
                $daily->activity_code = '02'.$daily->id.''.$tahun.''.$bulan;
                $save = $daily->save(false);

                if ($save) {

                    $dailyBudget = new ActivityDailyBudgetDepart();
                    $dailyBudget->department_budget_id = $idDep;
                    $dailyBudget->budget_value_sum = $post['source_value'];

                    $dailyBudget->activity_id = $daily->id;
                    $dailyBudget->save(false);

                    Yii::$app->getSession()->setFlash('success', 'Buat Data Kegiatan Berhasil');
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('create');
    }

    /**
     * Updates an existing ActivityDaily model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $role = Yii::$app->user->identity->roleName();

        if ($role == "Departemen") {
            $model = ActivityDaily::find()->where(['id' => $id])->one();
            $budget = ActivityDailyBudgetDepart::find()->where(['activity_id' => $model->id])->one();
            $awal = ActivityDailyBudgetDepart::find()->where(['department_budget_id' => $budget])->one();
            $baru = DepartmentBudget::find()->where(['id' => $awal->department_budget_id])->one();
            $reject = ActivityDailyReject::find()->where(['activity_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
            $range = $model->date_start . ' to ' . $model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_sum;
            $oldBudget = $baru->department_budget_value;

            if ($model->load(Yii::$app->request->post())) {
                $post = Yii::$app->request->post();

                $model->finance_status = 0;
                $model->department_status = 1;
                $model->chief_status = 0;
                $model->date_start = $post['from_date'];
                $model->date_end = $post['to_date'];
                $save = $model->save(false);

                if ($save && $budget->load(Yii::$app->request->post())) {

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

                    if ($reject != null) {
                        $reject->delete();
                    } else {
                        //no-action
                    }

                    Yii::$app->getSession()->setFlash('success', 'Update Data Kegiatan Rutin Berhasil');
                    return $this->redirect(['index']);
                }
            }
        }

        return $this->render('update', [
            'model' => $model,
            'budget' => $budget,
            'baru' => $baru,
            'range' => $range,
            'range_start' => $range_start,
            'range_end' => $range_end,
        ]);
    }

    /**
     * Displays a single ActivityDaily model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
      $role = Yii::$app->user->identity->role;

      // retrieve existing Deposit data
      $model = ActivityDaily::find()->where(['id' => $id])->one();

      if ($model->role == 7) {
          $budget = ActivityDailyBudgetDepart::find()->where(['activity_id' => $model->id])->one();
          $awal = ActivityDailyBudgetDepart::find()->where(['department_budget_id' => $budget])->one();
          $baru = DepartmentBudget::find()->where(['id' => $awal->department_budget_id])->one();
          $reject = ActivityDailyReject::find()->where(['activity_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->department_budget_value;
      }

        return $this->render('view', [
            'model' => $model,
            'budget' => $budget,
            'baru' => $baru,
            'range' => $range,
            'range_start' => $range_start,
            'range_end' => $range_end,
            'reject'=>$reject
        ]);
    }

    public function actionReport($id) {

        $role = Yii::$app->user->identity->role;

          $model = ActivityDaily::find()->where(['id'=>$id])->one();
          $budget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$model->id])->one();
          $awal = ActivityDailyBudgetDepart::find()->where(['department_budget_id'=>$budget])->one();
          $baru = DepartmentBudget::find()->where(['id'=>$awal->department_budget_id])->one();
          $department = Department::find()->where(['id'=>$model->department_code_id])->one();

        $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'department'=>$department,
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

    /**
     * Finds the ActivityDaily model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ActivityDaily the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ActivityDaily::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
