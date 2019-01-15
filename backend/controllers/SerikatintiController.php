<?php

namespace backend\controllers;

use Yii;
use common\models\Role;
use common\models\User;
use common\models\Registrasi;
use common\models\Secretariat;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

/**
 * SerikatintiController implements the CRUD actions for Serikatinti model.
 */
class SerikatintiController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
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
     * Lists all Serikatinti models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Role::find()->where(['in', 'id', [2,3,4,5]]),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Serikatinti model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id)
        ]);
    }

    /**
     * Creates a new Serikatinti model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {
        //     if (!$this->validate()) {
        //     return null;
        // }

            $password = 123456;
            $model->role = $id;
            $model->name = $model->name;
            $model->username = str_replace(" ","_",$model->name);
            $model->created_at = time();
            $model->updated_at = time();
            $model->setPassword($password);
            $model->generateAuthKey();
            $model->save();
            // var_dump($model);die;
            // return $this->redirect(['view', 'id' => $model->role])

            // if ($model->role == 4) {

            // $sekre = new Secretariat();
            // $sekre->secretariat_code = 'asdas12312';
            // $sekre->secretariat_name = 'asdasd12313';
            // $sekre->user_id = $id;
            // $sekre->save();

            // }

            return $this->redirect(['index']);
        }

        return $this->render('registrasi', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Serikatinti model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = User::find()->where(['role'=>$id])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->name = $model->name;
            $model->username = $model->name;
            $model->updated_at = time();
            $model->save(false);
            return $this->redirect(['view', 'id' => $model->role]);
        }

        return $this->render('registrasi', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Serikatinti model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // $this->findModel($id)->delete();

        $model = User::find()->where(['role'=>$id])->one();

        $model->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Serikatinti model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Serikatinti the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Role::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
