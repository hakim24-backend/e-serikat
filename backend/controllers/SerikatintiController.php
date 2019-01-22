<?php

namespace backend\controllers;

use Yii;
use common\models\Role;
use common\models\User;
use common\models\Registrasi;
use common\models\Secretariat;
use common\models\SecretariatBudget;
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
            $model->role = $id;
            $model->name = $model->name;
            $model->username = str_replace(" ","_",$model->name);
            $model->created_at = time();
            $model->updated_at = time();
            $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
            $model->generateAuthKey();
            $model->save(false);

            if ($model->role == 4) {

            $sekre = new Secretariat();
            $kodeSekretariat = 'Sekretariat-';
            $listSekretariat = Secretariat::find()->where(['LIKE','secretariat_code',$kodeSekretariat])->orderBy(['secretariat_code'=> SORT_DESC])->limit(1)->one();
            if ($listSekretariat == null) {
                $counter = '001';
            } else {
                $counter = explode('-', $listSekretariat['secretariat_code'])[1];
                $counter = str_pad($counter+1, 3, '0', STR_PAD_LEFT);
            }
                $code = $kodeSekretariat.''.$counter;

            $sekre->secretariat_code = $code;
            $sekre->secretariat_name = $model->name;
            $sekre->user_id = $model->id;
            $sekre->save(false);

            }

            return $this->redirect(['index']);
        }

        return $this->render('create', [
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
            $save = $model->save(false);

            if ($model->role == 4) {
            $sekre = Secretariat::find()->where(['user_id'=>$model->id])->one();
            $sekre->secretariat_name = $model->name;
            $sekre->save();
            }
            
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionUpdatePassword($id)
    {
        $model = $this->findModelUser($id);
        if ($model->load(Yii::$app->request->post())){
            if($model['currentPassword'] == NULL || $model['currentPassword'] == ""){
                \Yii::$app->getSession()->setFlash('danger', 'Kolom password kosong !');
                return $this->redirect(Yii::$app->request->referrer);
            }else{
                if (Yii::$app->getSecurity()->validatePassword($model['currentPassword'], $model['password_hash'])) {
                    // jika password sama
                    $model['password_hash'] = Yii::$app->getSecurity()->generatePasswordHash($model['newPassword']);
                    $model->save();
                    \Yii::$app->getSession()->setFlash('success', 'Password Telah Diganti');
                    return $this->redirect(Yii::$app->request->referrer);
                }else{
                    // Jika berbeda
                    \Yii::$app->getSession()->setFlash('error', 'Maaf Password yang anda masukan tidak cocok');
                    return $this->redirect(Yii::$app->request->referrer);
                }
            }
        }else{
            return $this->render('update_password', [
                'model' => $model,
            ]);
        }
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
        $model = User::find()->where(['role'=>$id])->one();
        $sekre = Secretariat::find()->where(['user_id'=>$model->id])->one();
        $sekreBudget = SecretariatBudget::find()->where(['secretariat_id'=>$sekre->id])->one();
        $permission = User::find()->where(['username'=>Yii::$app->user->identity->username])->andWhere(['id'=>$model])->one();

        if ($permission) {
            Yii::$app->getSession()->setFlash('error', "Tidak Bisa Hapus Karena Login");
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($sekreBudget) {
            $sekreBudget->delete();
            $sekre->delete();
            $model->delete();
            return $this->redirect(['index']);
            }elseif($sekre){
            $sekre->delete();
            $model->delete();
            return $this->redirect(['index']);
            }else{
            $model->delete();
            return $this->redirect(['index']);
            }
        }
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

    protected function findModelUser($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
