<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Activity;
use common\models\ActivityDaily;
use common\models\Chief;
use common\models\Department;
use common\models\Section;
use common\models\ActivityMainMember;
use yii\data\ActiveDataProvider;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['logout', 'index', 'cek'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $role = Yii::$app->user->identity->roles->id;
        if ($role==1) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            $dataKegiatan = Activity::find()->all();
            $dataKegiatanRutin = ActivityDaily::find()->all();
            return $this->render('index-sa',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
                'dataKegiatan'=>$dataKegiatan,
                'dataKegiatanRutin'=>$dataKegiatanRutin,
            ]);
        }elseif ($role==2) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            $dataKegiatan = Activity::find()->all();
            $dataKegiatanRutin = ActivityDaily::find()->all();
            return $this->render('index-ketum',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
                'dataKegiatan'=>$dataKegiatan,
                'dataKegiatanRutin'=>$dataKegiatanRutin,
            ]);
        }elseif ($role==3) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            $dataKegiatan = Activity::find()->all();
            $dataKegiatanRutin = ActivityDaily::find()->all(); 
            return $this->render('index-sekum',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
                'dataKegiatan'=>$dataKegiatan,
                'dataKegiatanRutin'=>$dataKegiatanRutin,
            ]);
        }elseif ($role==4) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            $dataKegiatan = Activity::find()->where(['role'=>4])->all();
            $dataKegiatanRutin = ActivityDaily::find()->where(['role'=>4])->all(); 
            return $this->render('index-sekretariat',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
                'dataKegiatan'=>$dataKegiatan,
                'dataKegiatanRutin'=>$dataKegiatanRutin,
            ]);
        }elseif ($role==5) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            $dataKegiatan = Activity::find()->where(['done'=>1])->all();
            $dataKegiatanRutin = ActivityDaily::find()->where(['done'=>1])->all();
            $kegiatanBelum = Activity::find()
            ->where(['done'=>0])
            ->andWhere(['<>','role',4])
            ->all();
            $kegiatanRutinBelum = ActivityDaily::find()
            ->where(['done'=>0])
            ->andWhere(['<>','role',4])
            ->all(); 
            return $this->render('index-bendahara',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
                'dataKegiatan'=>$dataKegiatan,
                'dataKegiatanRutin'=>$dataKegiatanRutin,
                'kegiatanBelum'=>$kegiatanBelum,
                'kegiatanRutinBelum'=>$kegiatanRutinBelum,
            ]);
        }elseif ($role==6) {
            $id_chief = Yii::$app->user->identity->chief->id;
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            $dataKegiatan = Activity::find()
            ->where(['done'=>1])
            ->andWhere(['chief_code_id'=>$id_chief])
            ->all();
            $dataKegiatanRutin = ActivityDaily::find()
            ->where(['done'=>1])
            ->andWhere(['chief_code_id'=>$id_chief])
            ->all();
            $kegiatanBelum = Activity::find()
            ->where(['done'=>0])
            ->andWhere(['<>','role',8])
            ->andWhere(['chief_code_id'=>$id_chief])
            ->all();
            $kegiatanRutinBelum = ActivityDaily::find()
            ->where(['done'=>0])
            ->andWhere(['<>','role',8])
            ->andWhere(['chief_code_id'=>$id_chief])
            ->all();
            return $this->render('index-ketua',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
                'dataKegiatan'=>$dataKegiatan,
                'dataKegiatanRutin'=>$dataKegiatanRutin,
                'kegiatanBelum'=>$kegiatanBelum,
                'kegiatanRutinBelum'=>$kegiatanRutinBelum,
            ]);
        }elseif ($role==7) {
            $id_department = Yii::$app->user->identity->department->id;
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            $dataKegiatan = Activity::find()
            ->where(['done'=>1])
            ->andWhere(['role'=>7])
            ->andWhere(['department_code_id'=>$id_department])
            ->all();
            $dataKegiatanSeksi = Activity::find()
            ->joinWith('activityBudgetSections')
            ->joinWith('activityBudgetSections.sectionBudget')
            ->joinWith('activityBudgetSections.sectionBudget.section')
            ->where(['done'=>1])
            ->andWhere(['role'=>8])
            ->andWhere(['section.id_depart'=>$id_department])
            ->all();
            $dataKegiatanRutin = ActivityDaily::find()
            ->where(['done'=>1])
            ->andWhere(['role'=>7])
            ->andWhere(['department_code_id'=>$id_department])
            ->all();
            $dataKegiatanRutinSeksi = ActivityDaily::find()
            ->joinWith('activityDailyBudgetSections')
            ->joinWith('activityDailyBudgetSections.sectionBudget')
            ->joinWith('activityDailyBudgetSections.sectionBudget.section')
            ->where(['done'=>1])
            ->andWhere(['role'=>8])
            ->andWhere(['section.id_depart'=>$id_department])
            ->all();
            $kegiatanBelum = Activity::find()
            ->where(['done'=>0])
            ->andWhere(['role'=>7])
            ->andWhere(['department_code_id'=>$id_department])
            ->all();
            $kegiatanBelumSeksi = Activity::find()
            ->joinWith('activityBudgetSections')
            ->joinWith('activityBudgetSections.sectionBudget')
            ->joinWith('activityBudgetSections.sectionBudget.section')
            ->where(['done'=>0])
            ->andWhere(['role'=>8])
            ->andWhere(['section.id_depart'=>$id_department])
            ->all();
            $kegiatanRutinBelum = ActivityDaily::find()
            ->where(['done'=>0])
            ->andWhere(['role'=>7])
            ->andWhere(['department_code_id'=>$id_department])
            ->all();
            $kegiatanRutinBelumSeksi = ActivityDaily::find()
            ->joinWith('activityDailyBudgetSections')
            ->joinWith('activityDailyBudgetSections.sectionBudget')
            ->joinWith('activityDailyBudgetSections.sectionBudget.section')
            ->where(['done'=>0])
            ->andWhere(['role'=>8])
            ->andWhere(['section.id_depart'=>$id_department])
            ->all(); 
            return $this->render('index-departemen',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
                'dataKegiatan'=>$dataKegiatan,
                'dataKegiatanSeksi'=>$dataKegiatanSeksi,
                'dataKegiatanRutin'=>$dataKegiatanRutin,
                'dataKegiatanRutinSeksi'=>$dataKegiatanRutinSeksi,
                'kegiatanBelum'=>$kegiatanBelum,
                'kegiatanBelumSeksi'=>$kegiatanBelumSeksi,
                'kegiatanRutinBelum'=>$kegiatanRutinBelum,
                'kegiatanRutinBelumSeksi'=>$kegiatanRutinBelumSeksi,
            ]);
        }elseif ($role==8) {
            $atasan = Yii::$app->user->identity->section->id_depart;
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            $dataKegiatan = Activity::find()
            ->joinWith('activityBudgetSections')
            ->joinWith('activityBudgetSections.sectionBudget')
            ->joinWith('activityBudgetSections.sectionBudget.section')
            ->where(['done'=>1])
            ->andWhere(['role'=>8])
            ->andWhere(['section.id_depart'=>$atasan])
            ->all();
            $dataKegiatanRutin = ActivityDaily::find()
            ->joinWith('activityDailyBudgetSections')
            ->joinWith('activityDailyBudgetSections.sectionBudget')
            ->joinWith('activityDailyBudgetSections.sectionBudget.section')
            ->where(['done'=>1])
            ->andWhere(['role'=>8])
            ->andWhere(['section.id_depart'=>$atasan])
            ->all();
            $kegiatanBelum = Activity::find()
            ->joinWith('activityBudgetSections')
            ->joinWith('activityBudgetSections.sectionBudget')
            ->joinWith('activityBudgetSections.sectionBudget.section')
            ->where(['done'=>0])
            ->andWhere(['role'=>8])
            ->andWhere(['section.id_depart'=>$atasan])
            ->all();
            $kegiatanRutinBelum = ActivityDaily::find()
            ->joinWith('activityDailyBudgetSections')
            ->joinWith('activityDailyBudgetSections.sectionBudget')
            ->joinWith('activityDailyBudgetSections.sectionBudget.section')
            ->where(['done'=>0])
            ->andWhere(['role'=>8])
            ->andWhere(['section.id_depart'=>$atasan])
            ->all(); 
            return $this->render('index-seksi',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
                'dataKegiatan'=>$dataKegiatan,
                'dataKegiatanRutin'=>$dataKegiatanRutin,
                'kegiatanBelum'=>$kegiatanBelum,
                'kegiatanRutinBelum'=>$kegiatanRutinBelum,
            ]);
        }
    }

    public function actionCek(){
        $model = new ActivityMainMember();
        return $this->render('cek', ['model'=>$model]);
    }
    /**
     * Login action.
     *
     * @return string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return string
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
