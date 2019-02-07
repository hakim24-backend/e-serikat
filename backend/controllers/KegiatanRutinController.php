<?php

namespace backend\controllers;

use Yii;
use common\models\ActivityDaily;
use common\models\Budget;
use common\models\Secretariat;
use common\models\Section;
use common\models\ActivityResponsibility;
use common\models\ActivityDailyResponsibility;
use common\models\ActivityDailyBudgetSecretariat;
use common\models\ActivityDailyBudgetSection;
use common\models\Approve;
use common\models\User;
use common\models\TransferRecord;
use common\models\SecretariatBudget;
use common\models\ChiefBudget;
use common\models\DepartmentBudget;
use common\models\SectionBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * KegiatanRutinController implements the CRUD actions for ActivityDaily model.
 */
class KegiatanRutinController extends Controller
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
        $role = Yii::$app->user->identity->roleName();

        if ($role == "Super Admin") {
            $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find(),
            ]);
        } elseif ($role == "Sekretariat") {
            $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()->where(['role'=>4]),
            ]);
        } elseif ($role == "Seksi") {
            $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find()->where(['role'=>8]),
            ]);
        } elseif ($role == "Bendahara") {
            $dataProvider = new ActiveDataProvider([
            'query' => ActivityDaily::find(),
            ]);
        }

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
        $role = Yii::$app->user->identity->roleName();

        if ($role == "Super Admin") {
            //sekretariat
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
            $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();

            //seksi
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
            $baru = SectionBudget::find()->where(['id'=>$awal])->one();
        } else if ($role == "Sekretariat") {
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
            $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
        } else if ($role == "Seksi") {
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
            $baru = SectionBudget::find()->where(['id'=>$awal])->one();
        }
        return $this->render('view', [
            'model' => $this->findModel($id),
            'budget' => $budget,
            'awal' => $awal,
            'baru' => $baru,
        ]);
    }

    /**
     * Creates a new ActivityDaily model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        if (Yii::$app->request->post()) {
            $role = Yii::$app->user->identity->roleName();

            if ($role == "Super Admin") {
                $idSekreBudget = 0;
                $post = Yii::$app->request->post();
                $data = SecretariatBudget::findOne($post['source_sdm']);

                if ($post['money_budget'] > $post['source_value']) {
                    Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                if ($post['source_value'] > $data->secretariat_budget_value ) {
                    Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                if ($post['jenis_sdm_source']=='4') {
                    $data = SecretariatBudget::findOne($post['source_sdm']);
                    // $valueNow = $data->secretariat_budget_value+(float)$post['source_value'];
                    $data->secretariat_budget_value=$data->secretariat_budget_value-(float)$post['money_budget'];
                    $data->save();
                    // $valueDP = (float)$post['source_value'];
                    $idSekreBudget = $data->id;
                }

                $daily = new ActivityDaily();
                $daily->finance_status = 0;
                $daily->department_status = 0;
                $daily->chief_status = 0;
                $daily->title = $post['judul'];
                $daily->description = $post['description'];
                $daily->role = 4;
                $daily->date_start = $post['from_date'];
                $daily->date_end = $post['to_date'];
                $daily->done = 0;
                $save = $daily->save(false);

                if ($save) {

                    $sekreBudget = new ActivityDailyBudgetSecretariat();
                    $sekreBudget->secretariat_budget_id = $idSekreBudget;
                    $sekreBudget->budget_value_dp = $post['money_budget'];
                    $sekreBudget->budget_value_sum = $post['source_value'];

                    $sekreBudget->activity_id = $daily->id;
                    $sekreBudget->save(false);

                    Yii::$app->getSession()->setFlash('success', 'Buat Data Kegiatan Berhasil');
                    return $this->redirect(['index']);
                }
            }

            else if ($role == "Sekretariat") {
                $idSekreBudget = 0;
                $post = Yii::$app->request->post();
                $data = SecretariatBudget::findOne($post['source_sdm']);

                if ($post['money_budget'] > $post['source_value']) {
                    Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                if ($post['source_value'] > $data->secretariat_budget_value ) {
                    Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                if ($post['jenis_sdm_source']=='4') {
                    $data = SecretariatBudget::findOne($post['source_sdm']);
                    // $valueNow = $data->secretariat_budget_value+(float)$post['source_value'];
                    $data->secretariat_budget_value=$data->secretariat_budget_value-(float)$post['money_budget'];
                    $data->save();
                    // $valueDP = (float)$post['source_value'];
                    $idSekreBudget = $data->id;
                }

                $daily = new ActivityDaily();
                $daily->finance_status = 0;
                $daily->department_status = 0;
                $daily->chief_status = 0;
                $daily->title = $post['judul'];
                $daily->description = $post['description'];
                $daily->role = 4;
                $daily->date_start = $post['from_date'];
                $daily->date_end = $post['to_date'];
                $daily->done = 0;
                $save = $daily->save(false);

                if ($save) {

                    $sekreBudget = new ActivityDailyBudgetSecretariat();
                    $sekreBudget->secretariat_budget_id = $idSekreBudget;
                    $sekreBudget->budget_value_dp = $post['money_budget'];
                    $sekreBudget->budget_value_sum = $post['source_value'];

                    $sekreBudget->activity_id = $daily->id;
                    $sekreBudget->save(false);

                    Yii::$app->getSession()->setFlash('success', 'Buat Data Kegiatan Berhasil');
                    return $this->redirect(['index']);
                }
            } else if ($role == "Seksi") {
                $idSeksi = 0;
                $post = Yii::$app->request->post();
                $data = SectionBudget::findOne($post['source_sdm']);

                if ($post['money_budget'] > $post['source_value']) {
                    Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                if ($post['source_value'] > $data->section_budget_value ) {
                    Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                    return $this->redirect(Yii::$app->request->referrer);
                }

                if ($post['jenis_sdm_source']=='8') {
                    $data = SectionBudget::findOne($post['source_sdm']);
                    // $valueNow = $data->secretariat_budget_value+(float)$post['source_value'];
                    $data->section_budget_value=$data->section_budget_value-(float)$post['money_budget'];
                    $data->save();
                    // $valueDP = (float)$post['source_value'];
                    $idSeksi = $data->id;
                }

                $daily = new ActivityDaily();
                $daily->finance_status = 0;
                $daily->department_status = 0;
                $daily->chief_status = 0;
                $daily->title = $post['judul'];
                $daily->description = $post['description'];
                $daily->role = 8;
                $daily->date_start = $post['from_date'];
                $daily->date_end = $post['to_date'];
                $daily->done = 0;
                $save = $daily->save(false);

                if ($save) {

                    $seksiBudget = new ActivityDailyBudgetSection();
                    $seksiBudget->section_budget_id = $idSeksi;
                    $seksiBudget->budget_value_dp = $post['money_budget'];
                    $seksiBudget->budget_value_sum = $post['source_value'];

                    $seksiBudget->activity_id = $daily->id;
                    $seksiBudget->save(false);

                    Yii::$app->getSession()->setFlash('success', 'Buat Data Kegiatan Berhasil');
                    return $this->redirect(['index']);
                }
            }
        }
        return $this->render('_form');
    }

    /**
     * Updates an existing ActivityDaily model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $role = Yii::$app->user->identity->roleName();

        if ($role == "Super Admin") {
            //sekretariat
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
            $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
            $range = $model->date_start.' to '.$model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_dp;
            $oldBudget = $baru->secretariat_budget_value;

            if ($model->load(Yii::$app->request->post())) {
                $post = Yii::$app->request->post();

                $model->date_start = $post['from_date'];
                $model->date_end = $post['to_date'];
                $save = $model->save(false);

                if ($save && $budget->load(Yii::$app->request->post())) {

                    $dp = $budget->budget_value_dp;
                    $total = $budget->budget_value_sum;
                    $modal = $baru->secretariat_budget_value;

                    if ($dp > $total) {
                        Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                        return $this->redirect(Yii::$app->request->referrer);
                    }

                    //nilai anggaran dp lebih kecil dari anggaran saat ini
                    if ($dp <= $modal) {
                        $dpBaru = $oldDP - $dp;
                        $oldBudgetBaru = $modal + $dpBaru;
                        if ($oldBudgetBaru <= 0) {
                            // var_dump($oldBudgetBaru);die();
                            Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    }

                    //nilai anggaran dp lebih besar dari anggaran saat ini
                    if ($dp >= $modal) {
                        $dpBaru = $dp - $oldDP;
                        $oldBudgetBaru = $modal - $dpBaru;
                        if ($oldBudgetBaru <= 0) {
                            // var_dump($oldBudgetBaru);die();
                            Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    }

                    $budget->budget_value_dp = $budget->budget_value_dp;
                    $budget->budget_value_sum = $budget->budget_value_sum;
                    $budget->save(false);

                    $baru->secretariat_budget_value = $oldBudgetBaru;
                    $baru->save(false);

                    Yii::$app->getSession()->setFlash('success', 'Update Data Kegiatan Rutin Berhasil');
                    return $this->redirect(['index']);
                }
            }
        } else if ($role == "Sekretariat") {
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
            $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
            $range = $model->date_start.' to '.$model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_dp;
            $oldBudget = $baru->secretariat_budget_value;

            if ($model->load(Yii::$app->request->post())) {
                $post = Yii::$app->request->post();

                $model->date_start = $post['from_date'];
                $model->date_end = $post['to_date'];
                $save = $model->save(false);

                if ($save && $budget->load(Yii::$app->request->post())) {

                    $dp = $budget->budget_value_dp;
                    $total = $budget->budget_value_sum;
                    $modal = $baru->secretariat_budget_value;

                    if ($dp > $total) {
                        Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                        return $this->redirect(Yii::$app->request->referrer);
                    }

                    
                    //nilai anggaran dp lebih kecil dari anggaran saat ini
                    if ($dp <= $modal) {
                        $dpBaru = $oldDP - $dp;
                        $oldBudgetBaru = $modal + $dpBaru;
                        if ($oldBudgetBaru <= 0) {
                            // var_dump($oldBudgetBaru);die();
                            Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    }

                    //nilai anggaran dp lebih besar dari anggaran saat ini
                    if ($dp >= $modal) {
                        $dpBaru = $dp - $oldDP;
                        $oldBudgetBaru = $modal - $dpBaru;
                        if ($oldBudgetBaru <= 0) {
                            // var_dump($oldBudgetBaru);die();
                            Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    }

                    $budget->budget_value_dp = $budget->budget_value_dp;
                    $budget->budget_value_sum = $budget->budget_value_sum;
                    $budget->save(false);

                    $baru->secretariat_budget_value = $oldBudgetBaru;
                    $baru->save(false);

                    Yii::$app->getSession()->setFlash('success', 'Update Data Kegiatan Rutin Berhasil');
                    return $this->redirect(['index']);
                }
            }
        } else if ($role == "Seksi") {
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
            $baru = SectionBudget::find()->where(['id'=>$awal])->one();
            $range = $model->date_start.' to '.$model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_dp;
            $oldBudget = $baru->section_budget_value;

            if ($model->load(Yii::$app->request->post())) {
                $post = Yii::$app->request->post();

                $model->date_start = $post['from_date'];
                $model->date_end = $post['to_date'];
                $save = $model->save(false);

                if ($save && $budget->load(Yii::$app->request->post())) {

                    $dp = $budget->budget_value_dp;
                    $total = $budget->budget_value_sum;
                    $modal = $baru->section_budget_value;

                    if ($dp > $total) {
                        Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                        return $this->redirect(Yii::$app->request->referrer);
                    }

                    //nilai anggaran dp lebih kecil dari anggaran saat ini
                    if ($oldBudget <= $dp) {
                        $dpBaru = $oldDP - $dp;
                        $oldBudgetBaru = $oldBudget + $dpBaru;
                        if ($oldBudgetBaru <= 0) {
                            var_dump($oldBudgetBaru);die();
                            Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    }

                    //nilai anggaran dp lebih besar dari anggaran saat ini
                    if ($oldBudget >= $dp) {
                        $dpBaru = $dp - $oldDP;
                        $oldBudgetBaru = $oldDP - $dpBaru;
                        if ($oldBudgetBaru <= 0) {
                            var_dump($oldBudgetBaru);die();
                            Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                            return $this->redirect(Yii::$app->request->referrer);
                        }
                    }

                    $budget->budget_value_dp = $budget->budget_value_dp;
                    $budget->budget_value_sum = $budget->budget_value_sum;
                    $budget->save(false);

                    $baru->section_budget_value = $oldBudgetBaru;
                    $baru->save(false);

                    Yii::$app->getSession()->setFlash('success', 'Update Data Kegiatan Rutin Berhasil');
                    return $this->redirect(['index']);
                }
            }
        }
        

        return $this->render('update', [
                'model' => $model,
                'budget' => $budget,
                'baru' => $baru,
                'range' => $range,
                'range_start' => $range_start,
                'range_end' => $range_end,
            ]);
    }

    /**
     * Deletes an existing ActivityDaily model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $role = Yii::$app->user->identity->roleName();

        if ($role == "Sekretariat") {
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
            $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
            $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$model])->one();
            $sekreBudget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
            if ($approve) {
                $approve->delete();
                $model->delete();
            }
            if ($sekreBudget) {
                $sekreBudget->delete();
                $model->delete();
            }
            $model->delete();

            $baru->secretariat_budget_value=$baru->secretariat_budget_value+$budget->budget_value_dp;
            $baru->save();

            Yii::$app->getSession()->setFlash('success', 'Hapus Data Kegiatan Berhasil');
            return $this->redirect(['index']);
        } else if ($role == "Seksi") {
            $model = ActivityDaily::find()->where(['id'=>$id])->one();
            $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$model])->one();
            $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
            $baru = SectionBudget::find()->where(['id'=>$awal])->one();
            $approve = ActivityDailyResponsibility::find()->where(['activity_id'=>$model])->one();
            $sekreBudget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$model])->one();
            if ($approve) {
                $approve->delete();
                $model->delete();
            }
            if ($sekreBudget) {
                $sekreBudget->delete();
                $model->delete();
            }
            $model->delete();

            $baru->section_budget_value=$baru->section_budget_value+$budget->budget_value_dp;
            $baru->save();

            Yii::$app->getSession()->setFlash('success', 'Hapus Data Kegiatan Berhasil');
            return $this->redirect(['index']);
        }
    }

    public function actionReport($id) {
    $role = Yii::$app->user->identity->roleName();

    if ($role == "Super Admin") {
        //sekretariat
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
        $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
        $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
        $sekre = Secretariat::find()->where(['id'=>$baru])->one();
        $sumber = Budget::find()->where(['id'=>$baru])->one();

        $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'sumber'=>$sumber,
            'sekre'=>$sekre
        ]);
        
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            // 'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Krajee Report Header'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
    }
    if ($role == "Sekretariat") {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
        $awal = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$budget])->one();
        $baru = SecretariatBudget::find()->where(['id'=>$awal])->one();
        $sekre = Secretariat::find()->where(['id'=>$baru])->one();
        $sumber = Budget::find()->where(['id'=>$baru])->one();

        $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'sumber'=>$sumber,
            'sekre'=>$sekre
        ]);
        
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            // 'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Krajee Report Header'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
    } else if ($role == "Seksi") {
        $model = ActivityDaily::find()->where(['id'=>$id])->one();
        $budget = ActivityDailyBudgetSection::find()->where(['activity_id'=>$model])->one();
        $awal = ActivityDailyBudgetSection::find()->where(['section_budget_id'=>$budget])->one();
        $baru = SectionBudget::find()->where(['id'=>$awal])->one();
        $sekre = Section::find()->where(['id'=>$baru])->one();
        $sumber = Budget::find()->where(['id'=>$baru])->one();

        $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'sumber'=>$sumber,
            'sekre'=>$sekre
        ]);
        
        // setup kartik\mpdf\Pdf component
        $pdf = new Pdf([
            // set to use core fonts only
            'mode' => Pdf::MODE_CORE, 
            // A4 paper format
            'format' => Pdf::FORMAT_A4, 
            // portrait orientation
            'orientation' => Pdf::ORIENT_PORTRAIT, 
            // stream to browser inline
            'destination' => Pdf::DEST_BROWSER, 
            // your html content input
            'content' => $content,  
            // format content from your own css file if needed or use the
            // enhanced bootstrap css built by Krajee for mPDF formatting 
            // 'cssFile' => '@vendor/kartik-v/yii2-mpdf/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}', 
             // set mPDF properties on the fly
            'options' => ['title' => 'Krajee Report Title'],
             // call mPDF methods on the fly
            'methods' => [ 
                'SetHeader'=>['Krajee Report Header'], 
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);
    }
    // return the pdf output as per the destination setting
    return $pdf->render(); 
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

    public function actionKodeTujuan($id)
    {
        if ($id=='4') {
            $data = SecretariatBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->secretariat_budget_code."</option>";
                }
                $budgetSekre = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$datas])->one();
            }
        }elseif ($id=='6') {
            $data = ChiefBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->chief_budget_code."</option>";
                }
            }
        }elseif ($id=='7') {
            $data = DepartmentBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->department_budget_code."</option>";
                }
            }
        }elseif ($id=='8') {
            $data = SectionBudget::find()->all();
            echo "<option value=0'> Pilih Kode Anggaran </option>";

            if ($data) {
                foreach ($data as $datas) {
                    echo "<option value='".$datas->id."'>".$datas->section_budget_code."</option>";
                }
            }
        }else{
            echo "<option value=0'> Pilih Kode Anggaran </option>";
        }
    }

    public function actionNilaiAnggaran(){
        $post = Yii::$app->request->post();
        if ($post['tipe']=='4') {
            $data = SecretariatBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            Rp.".$data->secretariat_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->secretariat_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }elseif ($post['tipe']=='6') {
            $data = ChiefBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$data->chief_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->chief_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }elseif ($post['tipe']=='7') {
            $data = DepartmentBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$data->department_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->department_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }elseif ($post['tipe']=='8') {
            $data = SectionBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            Rp.".$data->section_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->section_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }else{
            $datas['message']= "
             <div class='col-sm-12'>
                <div class='form-group'>
                    <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                    <div class='col-sm-8'>
                        0
                    </div>
                </div>
            </div>
            <br>
            <br>
            ";
            $datas['max']=0;
        }
        echo json_encode($datas);
    }

    public function actionNilaiAnggaranUpdate(){
        $post = Yii::$app->request->post();
        if ($post['tipe']=='4') {
            $data = SecretariatBudget::findOne($post['kode']);
            if ($data) {
                // $ativity = ActivityDaily::find()->where(['id'=>'id'])->one();
                $budgetSekre = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$data])->one();
                // $hasilDP = $budgetSekre->budget_value_sum - $budgetSekre->budget_value_dp;
                // $hasil =  ($hasilDP + $data->secretariat_budget_value) / 2;
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$data->section_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->section_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }elseif ($post['tipe']=='6') {
            $data = ChiefBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$data->chief_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->chief_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }elseif ($post['tipe']=='7') {
            $data = DepartmentBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$data->department_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->department_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }elseif ($post['tipe']=='8') {
            $data = SectionBudget::findOne($post['kode']);
            if ($data) {
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$data->section_budget_value."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$data->section_budget_value;
            }else{
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            0
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=0;
            }
        }else{
            $datas['message']= "
             <div class='col-sm-12'>
                <div class='form-group'>
                    <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                    <div class='col-sm-8'>
                        0
                    </div>
                </div>
            </div>
            <br>
            <br>
            ";
            $datas['max']=0;
        }
        echo json_encode($datas);
    }
}
