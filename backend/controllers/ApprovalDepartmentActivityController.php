<?php

namespace backend\controllers;

use Yii;
use common\models\Activity;
use common\models\ActivityReject;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
use common\models\ActivityResponsibility;
use common\models\ActivityMainMember;
use common\models\ActivityBudgetDepartment;
use common\models\DepartmentBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ApprovalDepartmentActivityResponsibilityController implements the CRUD actions for Activity model.
 */
class ApprovalDepartmentActivityController extends Controller
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
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Activity::find()->where(['done'=> 0])->andWhere(['role'=>7])->andWhere(['department_status'=>0]),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionApply($id)
    {
        $model = Activity::find()->where(['id'=>$id])->one();
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
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdateApply($id)
    {
        $model = Activity::find()->where(['id'=>$id])->one();
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
     * Deletes an existing Activity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionReject($id)
    {
        $model = new ActivityReject();
        $reject = Activity::find()->where(['id'=>$id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->message = $model->message;
            $model->activity_id = $id;
            $save = $model->save(false);

            if ($save) {
                $reject = Activity::find()->where(['id'=>$id])->one();
                $reject->done = 1;
                $reject->save(false);
            }

            $roleDepartment =  Activity::find()->where(['role'=>7])->one();

            $model = Activity::find()->where(['id'=>$id])->one();
            $budget = ActivityBudgetDepartment::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityBudgetDepartment::find()->where(['department_budget_id'=>$budget])->one();
            $baru = DepartmentBudget::find()->where(['id'=>$awal])->one();
            $approve = ActivityResponsibility::find()->where(['activity_id'=>$model])->one();
            $departBudget = ActivityBudgetDepartment::find()->where(['activity_id'=>$model])->one();
            $actitvitySection = ActivitySection::find()->where(['activity_id'=>$model])->one();
            $idActivitySection = ActivitySectionMember::find()->where(['section_activity_id'=>$actitvitySection])->one();
            $actitvitySectionMember = ActivitySectionMember::find()->where(['activity_id'=>$idActivitySection])->one();

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
                ActivityMainMember::deleteAll(['activity_id'=>$model]);
                $actitvitySectionMember->delete();
                $actitvitySection->delete();
            } else {
                $departBudget->delete();
                ActivityMainMember::deleteAll(['activity_id'=>$model]);
                $actitvitySectionMember->delete();
                $actitvitySection->delete();
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
     * Finds the Activity model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Activity the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Activity::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
