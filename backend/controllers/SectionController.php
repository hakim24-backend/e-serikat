<?php

namespace backend\controllers;

use Yii;
use common\models\Section;
use common\models\Department;
use common\models\Chief;
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
 * SectionController implements the CRUD actions for Section model.
 */
class SectionController extends Controller
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
                        'actions' => ['index','view','create','highlight','update','delete'],
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
     * Lists all Section models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Department::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionHighlight($id)
    {

        $model = new User();
        $dataProvider = new ActiveDataProvider([
            'query' => Section::find()->where(['id_depart'=>$id]),
        ]);

        return $this->render('highlight', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'id'=>$id
        ]);
    }

    /**
     * Displays a single Section model.
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
     * Creates a new Section model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $section = Section::find()->where(['id'=>$id])->one();
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {

            $password = 123456;
            $model->role = 8;
            $model->username = str_replace(" ","_",$model->name);
            $model->created_at = time();
            $model->updated_at = time();
            $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
            $model->generateAuthKey();
            $save = $model->save(false);


            if ($save) {
            $section = new Section();
            $depart = Department::find()->where(['id'=>$id])->one();
            $kodeSeksi = 'Seksi-';
            $listSeksi = Section::find()->where(['LIKE','section_code',$kodeSeksi])->orderBy(['section_code'=> SORT_DESC])->limit(1)->one();
            if ($listSeksi == null) {
                $counter = '001';
            } else {
                $counter = explode('-', $listSeksi['section_code'])[1];
                $counter = str_pad($counter+1, 3, '0', STR_PAD_LEFT);
            }
            $code = $kodeSeksi.''.$counter;
            $section->section_name = $model->name;
            $section->id_depart = $depart->id;
            $section->status_budget = 0;
            $section->section_code = $code;
            $section->user_id = $model->id;
            $section->save(false);
            }
            Yii::$app->getSession()->setFlash('success', 'Buat Akun Berhasil');
            return $this->redirect(['section/highlight/','id'=>$section->id_depart]);
        }
        return $this->render('create', [
            'model' => $model,
            // 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Section model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $section = Section::find()->where(['id'=>$id])->one();
        $model = User::find()->where(['id'=>$section->user_id])->one();
        // var_dump($section);die;

        if ($model->load(Yii::$app->request->post())) {
            $model->name = $model->name;
            $model->username = $model->name;
            $model->updated_at = time();
            $save = $model->save(false);

            if ($save) {
            $section->section_name = $model->name;
            $section->save(false);
            }
            Yii::$app->getSession()->setFlash('success', 'Update Data Berhasil');
            return $this->redirect(['section/highlight/','id'=>$section->id_depart]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Section model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $section = Section::find()->where(['id'=>$id])->one();
        $model = User::find()->where(['id'=>$section])->one();
        $user = User::find()->where(['username'=>Yii::$app->user->identity->username])->one();
        $id_user = User::find()->where(['id'=>$user])->one();
        $permission = Section::find()->where(['user_id'=>$id_user])->andWhere(['id'=>$id])->one();

        if ($permission) {
        Yii::$app->getSession()->setFlash('error', "Tidak Bisa Hapus Karena Login");
        return $this->redirect(Yii::$app->request->referrer);
        } else {
        $section -> delete();
        $model->delete();
        Yii::$app->getSession()->setFlash('success', 'Hapus Data Berhasil');
        return $this->redirect(['section/highlight/','id'=>$section->id_depart]);
        }
    }

    /**
     * Finds the Section model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Section the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Section::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
