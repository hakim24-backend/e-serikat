<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use common\models\Chief;
use common\models\Department;
use common\models\Section;
use common\models\ActivityMainMember;

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
            // $kegiatan = 
            return $this->render('index-sa',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
            ]);
        }elseif ($role==2) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            // $kegiatan = 
            return $this->render('index-ketum',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
            ]);
        }elseif ($role==3) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            // $kegiatan = 
            return $this->render('index-sekum',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
            ]);
        }elseif ($role==4) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            // $kegiatan = 
            return $this->render('index-sekretariat',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
            ]);
        }elseif ($role==5) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            // $kegiatan = 
            return $this->render('index-bendahara',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
            ]);
        }elseif ($role==6) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            // $kegiatan = 
            return $this->render('index-ketua',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
            ]);
        }elseif ($role==7) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            // $kegiatan = 
            return $this->render('index-departemen',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
            ]);
        }elseif ($role==8) {
            $jumlahKetua = Chief::find()->count('id');
            $jumlahDepartemen = Department::find()->count('id');
            $jumlahSeksi = Section::find()->count('id');
            // $kegiatan = 
            return $this->render('index-seksi',[
                'jumlahKetua'=>$jumlahKetua,
                'jumlahDepartemen' => $jumlahDepartemen,
                'jumlahSeksi' => $jumlahSeksi,
            ]);
        }
        // $this->redirect('serikatinti/');
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
