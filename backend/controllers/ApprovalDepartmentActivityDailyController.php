<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\ActivityDailyReject;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityDailyBudgetDepart;
use common\models\DepartmentBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ApprovalDepartmentActivityDailyController implements the CRUD actions for ActivityDaily model.
 */
class ApprovalDepartmentActivityDailyController extends Controller
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
     * Lists all ActivityDaily models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
        'query' => ActivityDaily::find()->where(['done'=> 0])->andWhere(['role'=>7])->andWhere(['department_status'=>0]),
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ActivityDaily model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionApply($id)
    {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $model->department_status = 1;
        $model->save(false);
        $status = $model->finance_status;
        Yii::$app->getSession()->setFlash('success', 'Kegiatan Rutin Berhasil Disetujui');
        return $this->redirect(Yii::$app->request->referrer);
        return $this->render([
            'model' => $model,
            'status' => $status
        ]);
    }

    /**
     * Updates an existing ActivityDaily model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateApply($id)
    {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $model->department_status = 0;
        $model->save(false);
        $status = $model->finance_status;
        Yii::$app->getSession()->setFlash('info', 'Kegiatan Rutin Berhasil Diedit');
        return $this->redirect(Yii::$app->request->referrer);
        return $this->render([
            'model' => $model,
            'status' => $status
        ]);
    }

    /**
     * Deletes an existing ActivityDaily model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReject($id)
    {
        $model = new ActivityDailyReject();
        $reject = ActivityDaily::find()->where(['id'=>$id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->message = $model->message;
            $model->activity_id = $id;
            $save = $model->save(false);

            if ($save) {
                $reject = ActivityDaily::find()->where(['id'=>$id])->one();
                $reject->done = 1;
                $reject->save(false);
            }

            $roleDepartment =  ActivityDaily::find()->where(['role'=>7])->one();

            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetDepart::find()->where(['department_budget_id'=>$budget])->one();
            $baru = DepartmentBudget::find()->where(['id'=>$awal])->one();
            $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$model])->one();
            $departBudget = ActivityDailyBudgetDepart::find()->where(['activity_id'=>$model])->one();

            $model->chief_status=0;
            $model->department_status=0;
            $model->save(false);

            if ($approve) {
                $uploadPath = Yii::getAlias('@backend')."/web/template";
                $oldfile = $approve->file;
                $oldPhoto = $approve->photo;
                unlink($uploadPath.$oldfile);
                unlink($uploadPath.$oldPhoto);
                $approve->delete();
                $departBudget->delete();
            } else {
                $departBudget->delete();
            }

            $baru->department_budget_value=$baru->department_budget_value+$budget->budget_value_dp;
            $baru->save();

        Yii::$app->getSession()->setFlash('info', 'Kegiatan Berhasil Ditolak');
        return $this->redirect(['index']);

        }

        return $this->render('_form', [
            'model' => $model,
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
