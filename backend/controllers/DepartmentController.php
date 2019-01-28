<?php

namespace backend\controllers;

use Yii;
use common\models\Department;
use common\models\Chief;
use common\models\Section;
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
 * DepartmentController implements the CRUD actions for Department model.
 */
class DepartmentController extends Controller
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
     * Lists all Department models.
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

    public function actionHighlight($id)
    {
    
        $model = new User();
        $dataProvider = new ActiveDataProvider([
            'query' => Department::find()->where(['id_chief'=>$id]),
        ]);
            return $this->render('highlight', [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'id'=>$id
        ]);
    }

    /**
     * Displays a single Department model.
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
     * Creates a new Department model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        
        $depart = Department::find()->where(['id'=>$id])->one();
        $model = new User();

        if ($model->load(Yii::$app->request->post())) {

            $password = 123456;
            $model->role = 7;   
            $model->username = str_replace(" ","_",$model->name);
            $model->created_at = time();
            $model->updated_at = time();
            $model->password_hash = Yii::$app->getSecurity()->generatePasswordHash($password);
            $model->generateAuthKey();
            $save = $model->save(false);


            if ($save) {
            $depart = new Department();
            $chief = Chief::find()->where(['id'=>$id])->one();
            $kodeDepartemen = 'Departemen-';
            $listDepartemen = Department::find()->where(['LIKE','depart_code',$kodeDepartemen])->orderBy(['depart_code'=> SORT_DESC])->limit(1)->one();
            if ($listDepartemen == null) {
                $counter = '001';
            } else {
                $counter = explode('-', $listDepartemen['depart_code'])[2];
                $counter = str_pad($counter+1, 3, '0', STR_PAD_LEFT);
            }
            $code = $kodeDepartemen.''.$counter;
            $depart->depart_name = $model->name;
            $depart->id_chief = $chief->id;
            $depart->status_budget = 0;
            $depart->depart_code = $code;
            $depart->user_id = $model->id;
            $depart->save(false);

            }
            Yii::$app->getSession()->setFlash('success', 'Buat Akun Berhasil');
            return $this->redirect(['department/highlight/','id'=>$depart->id_chief]);
        }
        return $this->render('create', [
            'model' => $model,
            // 'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing Department model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $depart = Department::find()->where(['id'=>$id])->one();
        $model = User::find()->where(['id'=>$depart])->one();

        if ($model->load(Yii::$app->request->post())) {
            $model->name = $model->name;
            $model->username = $model->name;
            $model->updated_at = time();
            $save = $model->save(false);

            if ($save) {
            $depart->depart_name = $model->name;
            $depart->save(false);
            }
            Yii::$app->getSession()->setFlash('success', 'Update Data Berhasil');
            return $this->redirect(['department/highlight/','id'=>$depart->id_chief]);

        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Department model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $depart = Department::find()->where(['id'=>$id])->one();
        $section = Section::find()->where(['id_depart'=>$depart])->one();        
        $model = User::find()->where(['id'=>$depart])->one();
        $user = User::find()->where(['username'=>Yii::$app->user->identity->username])->one();
        $id_user = User::find()->where(['id'=>$user])->one();
        $permission = Department::find()->where(['user_id'=>$id_user])->andWhere(['id'=>$id])->one();

        if ($permission) {
            Yii::$app->getSession()->setFlash('error', "Tidak Bisa Hapus Karena Login");
            return $this->redirect(Yii::$app->request->referrer);
        } else {
            if ($section) {
            Yii::$app->getSession()->setFlash('error', "Harus hapus data di section");
            return $this->redirect(Yii::$app->request->referrer);
            } else{
            $depart -> delete();
            $model-> delete();
            Yii::$app->getSession()->setFlash('success', 'Hapus Data Berhasil');
            return $this->redirect(['department/highlight/','id'=>$depart->id_chief]);
            }
        }
    }

    /**
     * Finds the Department model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Department the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Department::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
