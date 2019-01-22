<?php

namespace backend\controllers;

use Yii;
use common\models\Chief;
use common\models\Department;
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
 * ChiefController implements the CRUD actions for Chief model.
 */
class ChiefController extends Controller
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
     * Lists all Chief models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Chief::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Chief model.
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
     * Creates a new Chief model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        
        $user = User::find()->all();
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {

            foreach ($user as $value) {
                if ( $value->name == $model->name) {
                    Yii::$app->getSession()->setFlash('error', "Tidak Bisa Create Karena Nama Sama");
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }

            $password = 123456;
            $model->role = 6;
            $model->username = str_replace(" ","_",$model->name);
            $model->created_at = time();
            $model->updated_at = time();
            $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
            $model->generateAuthKey();
            $save = $model->save(false);


            if ($save) {
            $chief = new Chief();

            $kodeKetua = 'Ketua-';
            $listKetua = Chief::find()->where(['LIKE','chief_code',$kodeKetua])->orderBy(['chief_code'=> SORT_DESC])->limit(1)->one();
            if ($listKetua == null) {
                $counter = '001';
            } else {
                $counter = explode('-', $listKetua['chief_code'])[1];
                $counter = str_pad($counter+1, 3, '0', STR_PAD_LEFT);
            }
                $code = $kodeKetua.''.$counter;

            $chief->chief_name = $model->name;
            $chief->chief_code = $code;
            $chief->status_budget = 0;
            $chief->user_id = $model->id;
            $chief->save(false);

            }

            return $this->redirect(['index']);
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Chief model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $chief = Chief::find()->where(['id'=>$id])->one();
        $model = User::find()->where(['id'=>$chief])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->name = $model->name;
            $model->username = $model->name;
            $model->updated_at = time();
            $save = $model->save(false);

            if ($save) {
            $chief->chief_name = $model->name;
            $chief->save(false);
            }

            return $this->redirect(['index']);

        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Chief model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $chief = Chief::find()->where(['id'=>$id])->one();
        $department = Department::find()->where(['id_chief'=>$chief])->one();
        $model = User::find()->where(['id'=>$chief])->one();
        $user = User::find()->where(['username'=>Yii::$app->user->identity->username])->one();
        $id_user = User::find()->where(['id'=>$user])->one();
        $permission = Chief::find()->where(['user_id'=>$id_user])->andWhere(['id'=>$id])->one();

        if ($permission) {
            Yii::$app->getSession()->setFlash('error', "Tidak Bisa Hapus Karena Login");
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($department) {
            Yii::$app->getSession()->setFlash('error', "Harus hapus data di departemen");
            return $this->redirect(Yii::$app->request->referrer);
            } else {
            $chief -> delete();
            $model->delete();
            return $this->redirect(['index']);
            }
        }
    }

    /**
     * Finds the Chief model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Chief the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Chief::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
