<?php

namespace backend\controllers;

use common\models\ActivityDaily;
use common\models\ActivityDailyBudgetDepart;
use common\models\DepartmentBudget;
use common\models\User;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;

class ActivityDailyDepartmentController extends \yii\web\Controller
{
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

    public function actionCreate()
    {
        if (Yii::$app->request->post()) {

            $role = Yii::$app->user->identity->roleName();

            if ($role == "Departemen") {
                $idDep = 0;
                $post = Yii::$app->request->post();
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

                    $idDep = $data->id;
                }
                $id_user = Yii::$app->user->identity->id;
                $depId = \common\models\Department::find()->where(['user_id' => $id_user])->one();
                $chiefId = \common\models\Chief::find()->where(['id' => $depId->id_chief])->one();

                $daily = new ActivityDaily();
                $daily->finance_status = 0;
                $daily->department_status = 0;
                $daily->chief_status = 0;
                $daily->title = $post['judul'];
                $daily->description = $post['description'];
                $daily->role = 7;
                $daily->date_start = $post['from_date'];
                $daily->date_end = $post['to_date'];
                $daily->done = 0;
                $daily->department_code_id = $depId->id;
                $daily->chief_code_id = $chiefId->id;

                $save = $daily->save(false);

                if ($save) {

                    $dailyBudget = new ActivityDailyBudgetDepart();
                    $dailyBudget->department_budget_id = $idDep;
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

        if ($role == "Departemen") {
            $model = ActivityDaily::find()->where(['id' => $id])->one();
            $budget = ActivityDailyBudgetDepart::find()->where(['activity_id' => $model])->one();
            $awal = ActivityDailyBudgetDepart::find()->where(['department_budget_id' => $budget])->one();
            $baru = DepartmentBudget::find()->where(['id' => $awal])->one();
            $range = $model->date_start . ' to ' . $model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_dp;
            $oldBudget = $baru->department_budget_value;

            if ($model->load(Yii::$app->request->post())) {
                $post = Yii::$app->request->post();

                $model->date_start = $post['from_date'];
                $model->date_end = $post['to_date'];
                $save = $model->save(false);

                if ($save && $budget->load(Yii::$app->request->post())) {

                    $dp = $budget->budget_value_dp;
                    $total = $budget->budget_value_sum;
                    $modal = $baru->department_budget_value;

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

                    $baru->department_budget_value = $oldBudgetBaru;
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
     * Displays a single ActivityDaily model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $role = Yii::$app->user->identity->roleName();

        $model = ActivityDaily::find()->where(['id' => $id])->one();
        $budget = ActivityDailyBudgetDepart::find()->where(['activity_id' => $model])->one();
        $awal = ActivityDailyBudgetDepart::find()->where(['department_budget_id' => $budget])->one();
        $baru = DepartmentBudget::find()->where(['id' => $awal])->one();

        return $this->render('view', [
            'model' => $model,
            'budget' => $budget,
            'awal' => $awal,
            'baru' => $baru,
        ]);
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
