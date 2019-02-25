<?php

namespace backend\controllers;

use Yii;
use common\models\Budget;
use common\models\ChiefBudget;
use common\models\DepartmentBudget;
use common\models\SecretariatBudget;
use common\models\SectionBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * BudgetController implements the CRUD actions for Budget model.
 */
class BudgetController extends Controller
{
    /**
     * {@inheritdoc}
     */
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
                        'actions' => ['logout','index','view','create','update','delete'],
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

    /**
     * Lists all Budget models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Budget::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Budget model.
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
     * Creates a new Budget model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Budget();

        if ($model->load(Yii::$app->request->post())) {
            $model->budget_rek = date_timestamp_get(date_create());
            $model->budget_code = $model->budget_rek.'-'.$model->budget_year;
            $model->save(false);
            Yii::$app->getSession()->setFlash('success', 'Buat Data Sumber Dana Berhasil');
            return $this->redirect(['index']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Budget model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->getSession()->setFlash('success', 'Update Data Sumber Dana Berhasil');
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Budget model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $danaChief = ChiefBudget::find()->where(['chief_budget_id'=>$id])->one();
        $danaDepart = DepartmentBudget::find()->where(['department_budget_id'=>$id])->one();
        $danaSeksi = SectionBudget::find()->where(['section_budget_id'=>$id])->one();
        $danaSekre = SecretariatBudget::find()->where(['secretariat_budget_id'=>$id])->one();

        if ($danaChief) {
            Yii::$app->getSession()->setFlash('error', 'Tidak Bisa Hapus Karena Ada Data Di Tabel Chief Budget');
            return $this->redirect(['index']);
        }
        if ($danaDepart) {
            Yii::$app->getSession()->setFlash('error', 'Tidak Bisa Hapus Karena Ada Data Di Tabel Department Budget');
            return $this->redirect(['index']);
        }
        if ($danaSeksi) {
            Yii::$app->getSession()->setFlash('error', 'Tidak Bisa Hapus Karena Ada Data Di Tabel Section Budget');
            return $this->redirect(['index']);
        }
        if ($danaSekre) {
            Yii::$app->getSession()->setFlash('error', 'Tidak Bisa Hapus Karena Ada Data Di Tabel Secretariat Budget');
            return $this->redirect(['index']);
        }
        $this->findModel($id)->delete();

        Yii::$app->getSession()->setFlash('success', 'Hapus Data Sumber Dana Berhasil');
        return $this->redirect(['index']);
    }

    /**
     * Finds the Budget model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Budget the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Budget::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
