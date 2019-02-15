<?php

namespace backend\controllers;

use common\models\ActivityDaily;
use common\models\ActivityDailyBudgetChief;
use common\models\ActivityBudgeDChief;
use common\models\ChiefBudget;
use common\models\Chief;
use common\models\Budget;
use common\models\User;
use kartik\mpdf\Pdf;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

/**
 * ActivityDailyChiefController implements the CRUD actions for ActivityDaily model.
 */
class ActivityDailyChiefController extends Controller
{

    /**
     * Lists all ActivityDaily models.
     * @return mixed
     */
    public function actionIndex()
    {
        $role = Yii::$app->user->identity->role;

        $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()->where(['role' => $role]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        $role = Yii::$app->user->identity->roleName();

        $model = ActivityDaily::find()->where(['id' => $id])->one();
        $budget = ActivityDailyBudgetChief::find()->where(['activity_id' => $model])->one();
        $awal = ActivityDailyBudgetChief::find()->where(['chief_budget_id' => $budget])->one();
        $baru = ChiefBudget::find()->where(['id' => $awal])->one();

        return $this->render('view', [
            'model' => $model,
            'budget' => $budget,
            'awal' => $awal,
            'baru' => $baru,
        ]);
    }

    /**
     * Creates a new ActivityDaily model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->post()) {

            $role = Yii::$app->user->identity->roleName();

            if ($role == "Ketua") {
                $idDep = 0;
                $post = Yii::$app->request->post();
                $data = ChiefBudget::findOne($post['source_sdm']);

                if ($post['money_budget'] > $post['source_value']) {
                    Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                if ($post['source_value'] > $data->chief_budget_value) {
                    Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                if ($post['jenis_sdm_source'] == '6') {
                    $data = ChiefBudget::findOne($post['source_sdm']);

                    $data->chief_budget_value = $data->chief_budget_value - (float) $post['money_budget'];
                    $data->save();

                    $idDep = $data->id;
                }
                $id_user = Yii::$app->user->identity->id;
                $chiefId = \common\models\Chief::find()->where(['user_id' => $id_user])->one();
                $depId = \common\models\Department::find()->where(['id_chief' => $chiefId->id])->one();

                $daily = new ActivityDaily();
                $daily->finance_status = 0;
                $daily->department_status = 0;
                $daily->chief_status = 0;
                $daily->title = $post['judul'];
                $daily->description = $post['description'];
                $daily->role = 6;
                $daily->date_start = $post['from_date'];
                $daily->date_end = $post['to_date'];
                $daily->done = 0;
                $daily->department_code_id = $depId->id;
                $daily->chief_code_id = $chiefId->id;

                $save = $daily->save(false);

                if ($save) {

                    $dailyBudget = new ActivityDailyBudgetChief();
                    $dailyBudget->chief_budget_id = $idDep;
                    $dailyBudget->budget_value_dp = $post['money_budget'];
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

        if ($role == "Ketua") {
            $model = ActivityDaily::find()->where(['id' => $id])->one();
            $budget = ActivityDailyBudgetChief::find()->where(['activity_id' => $model])->one();
            $awal = ActivityDailyBudgetChief::find()->where(['chief_budget_id' => $budget])->one();
            $baru = ChiefBudget::find()->where(['id' => $awal])->one();
            $range = $model->date_start . ' to ' . $model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_dp;
            $oldBudget = $baru->chief_budget_value;

            if ($model->load(Yii::$app->request->post())) {
                $post = Yii::$app->request->post();

                $model->date_start = $post['from_date'];
                $model->date_end = $post['to_date'];
                $save = $model->save(false);

                if ($save && $budget->load(Yii::$app->request->post())) {

                    $dp = $budget->budget_value_dp;
                    $total = $budget->budget_value_sum;
                    $modal = $baru->chief_budget_value;

                    if ($dp > $total) {
                        Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                        return $this->redirect(Yii::$app->request->referrer);
                    }

                    //nilai anggaran dp lebih kecil dari anggaran saat ini
                    if ($oldBudget <= $dp) {
                        $dpBaru = $oldDP - $dp;
                        $oldBudgetBaru = $oldBudget + $dpBaru;
                        if ($oldBudgetBaru <= 0) {
                            var_dump($oldBudgetBaru);die();
                            Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    }

                    //nilai anggaran dp lebih besar dari anggaran saat ini
                    if ($oldBudget >= $dp) {
                        $dpBaru = $dp - $oldDP;
                        $oldBudgetBaru = $oldDP - $dpBaru;
                        if ($oldBudgetBaru <= 0) {
                            var_dump($oldBudgetBaru);die();
                            Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    }

                    $budget->budget_value_dp = $budget->budget_value_dp;
                    $budget->budget_value_sum = $budget->budget_value_sum;
                    $budget->save(false);

                    $baru->chief_budget_value = $oldBudgetBaru;
                    $baru->save(false);

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
     * Deletes an existing ActivityDaily model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionReport($id) {

        $role = Yii::$app->user->identity->role;

          $model = ActivityDaily::find()->where(['id'=>$id])->one();
          $budget = ActivityDailyBudgetChief::find()->where(['activity_id'=>$model])->one();
          $awal = ActivityDailyBudgetChief::find()->where(['chief_budget_id'=>$budget])->one();
          $baru = ChiefBudget::find()->where(['id'=>$awal])->one();
          $sekre = Chief::find()->where(['id'=>$baru])->one();
          $sumber = Budget::find()->where(['id'=>$baru])->one();

        $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'sumber'=>$sumber,
            'sekre'=>$sekre
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
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [
                'SetHeader'=>['Krajee Report Header'],
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
