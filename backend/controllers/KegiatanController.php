<?php

namespace backend\controllers;

use Yii;
use common\models\Model;
use common\models\Activity;
use common\models\ActivityMainMember;
use common\models\ActivitySection;
use common\models\ActivitySectionMember;
use common\models\ActivityResponsibility;
use common\models\ActivityBudgetSecretariat;
use common\models\ActivityBudgetSection;
use common\models\ActivityBudgetDepartment;
use common\models\ActivityReject;
use common\models\ActivityBudgetChief;
use common\models\Approve;
use common\models\User;
use common\models\Section;
use common\models\Secretariat;
use common\models\Department;
use common\models\Budget;
use common\models\TransferRecord;
use common\models\SecretariatBudget;
use common\models\ChiefBudget;
use common\models\DepartmentBudget;
use common\models\SectionBudget;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;
/**
 * KegiatanController implements the CRUD actions for Activity model.
 */
class KegiatanController extends Controller
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
                        'actions' => ['logout','index','view','create','update','delete','report','save-deposit','kode-tujuan','nilai-anggaran','nilai-anggaran-update'],
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
     * Lists all Activity models.
     * @return mixed
     */
    public function actionIndex()
    {
        $role = Yii::$app->user->identity->role;
        // $department = Yii::$app->user->identity->section->id_depart;
        if($role != 1 && $role != 5 && $role != 2 && $role !=3){
          if($role == 8){
            $atasan = Yii::$app->user->identity->section->id_depart;
            $dataProvider = new ActiveDataProvider([
              'query' => Activity::find()
                        ->joinWith('activityBudgetSections')
                        ->joinWith('activityBudgetSections.sectionBudget')
                        ->joinWith('activityBudgetSections.sectionBudget.section')
                        ->where(['activity.role'=>$role])
                        ->andWhere(['section.id_depart'=>$atasan]),
            ]);

          }else if($role == 7){
            $atasan = Yii::$app->user->identity->department->id_chief;
            $dataProvider = new ActiveDataProvider([
              'query' => Activity::find()
                        ->joinWith('activityBudgetDepartments')
                        ->joinWith('activityBudgetDepartments.departmentBudget')
                        ->joinWith('activityBudgetDepartments.departmentBudget.department')
                        ->where(['activity.role'=>$role])
                        ->andWhere(['department.id_chief'=>$atasan]),
            ]);
          }else if($role == 4){
            $dataProvider = new ActiveDataProvider([
              'query' =>  Activity::find()->where(['role'=>4])->andWhere(['chief_status'=>1])->andWhere(['department_status'=>1]),
            ]);
          }
        }else{
          $dataProvider = new ActiveDataProvider([
            'query' => Activity::find(),
          ]);
        }

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
$role = Yii::$app->user->identity->role;

      // retrieve existing Deposit data
      $model = Activity::find()->where(['id' => $id])->one();

      $ketua = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Ketua"])->one();
      $wakil = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Wakil"])
          ->one();
      $sekretaris = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Sekretaris"])
          ->one();
      $bendahara = ActivityMainMember::find()
          ->where(['activity_id' => $id])
          ->andWhere(['name_committee' => "Bendahara"])
          ->one();

      if ($role == 4) {
          $budget = ActivityBudgetSecretariat::find()->where(['activity_id' => $model->id])->one();
          $baru = SecretariatBudget::find()->where(['id' => $budget->secretariat_budget_id])->one();
          $reject = ActivityReject::find()->where(['activity_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->secretariat_budget_value;
      } elseif ($role == 8) {
          $budget = ActivityBudgetSection::find()->where(['activity_id' => $model->id])->one();
          $baru = SectionBudget::find()->where(['id' => $budget->section_budget_id])->one();
          $reject = ActivityReject::find()->where(['activity_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
          $range = $model->date_start . ' to ' . $model->date_end;
          $range_start = $model->date_start;
          $range_end = $model->date_end;
          $oldDP = $budget->budget_value_dp;
          $oldBudget = $baru->section_budget_value;
      }else if ($role==3 || $role==2) {
        if ($model->role == 4) {
              $budget = ActivityBudgetSecretariat::find()->where(['activity_id'=>$model->id])->one();
              $baru = SecretariatBudget::find()->where(['id'=>$budget->secretariat_budget_id])->one();
              $reject = ActivityReject::find()->where(['activity_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
              $range = $model->date_start . ' to ' . $model->date_end;
              $range_start = $model->date_start;
              $range_end = $model->date_end;
              $oldDP = $budget->budget_value_dp;
              $oldBudget = $baru->secretariat_budget_value;
          } else if ($model->role == 6) {
              $budget = ActivityBudgetChief::find()->where(['activity_id'=>$model->id])->one();
              $baru = ChiefBudget::find()->where(['id'=>$budget->chief_budget_id])->one();
              $reject = ActivityReject::find()->where(['activity_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
              $range = $model->date_start . ' to ' . $model->date_end;
              $range_start = $model->date_start;
              $range_end = $model->date_end;
              $oldDP = $budget->budget_value_dp;
              $oldBudget = $baru->chief_budget_value;
          }else if ($model->role == 7) {
              $budget = ActivityBudgetDepartment::find()->where(['activity_id'=>$model->id])->one();
              $baru = DepartmentBudget::find()->where(['id'=>$budget->department_budget_id])->one();
              $reject = ActivityReject::find()->where(['activity_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
              $range = $model->date_start . ' to ' . $model->date_end;
              $range_start = $model->date_start;
              $range_end = $model->date_end;
              $oldDP = $budget->budget_value_dp;
              $oldBudget = $baru->department_budget_value;
          }else if ($model->role == 8) {
              $budget = ActivityBudgetSection::find()->where(['activity_id'=>$model->id])->one();
              $baru = SectionBudget::find()->where(['id'=>$budget->section_budget_id])->one();
              $reject = ActivityReject::find()->where(['activity_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
              $range = $model->date_start . ' to ' . $model->date_end;
              $range_start = $model->date_start;
              $range_end = $model->date_end;
              $oldDP = $budget->budget_value_dp;
              $oldBudget = $baru->section_budget_value;
          }

      }

      // retrieve existing ActivitySection data
      $oldActivitySectionIds = ActivitySection::find()->select('id')
          ->where(['activity_id' => $id])->asArray()->all();
      $oldActivitySectionIds = ArrayHelper::getColumn($oldActivitySectionIds, 'id');
      $modelsSection = ActivitySection::findAll(['id' => $oldActivitySectionIds]);
      $modelsSection = (empty($modelsSection)) ? [new ActivitySection] : $modelsSection;

      // retrieve existing Loads data
      $oldLoadIds = [];
      foreach ($modelsSection as $i => $modelSection) {
          $oldLoads = ActivitySectionMember::findAll(['section_activity_id' => $modelSection->id]);
          $modelsMember[$i] = $oldLoads;
          $oldLoadIds = array_merge($oldLoadIds, ArrayHelper::getColumn($oldLoads, 'id'));
          $modelsMember[$i] = (empty($modelsMember[$i])) ? [new ActivitySectionMember] : $modelsMember[$i];
      }


        return $this->render('view', [
            'model' => $model,
            'budget' => $budget,
            'baru' => $baru,
            'ketua' => $ketua,
            'wakil' => $wakil,
            'sekretaris' => $sekretaris,
            'bendahara' => $bendahara,
            'modelsSection' => $modelsSection,
            'modelsMember' => $modelsMember,
            'range' => $range,
            'range_start' => $range_start,
            'range_end' => $range_end,
            'reject'=>$reject
        ]);
    }

    /**
     * Creates a new Activity model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */

     public function actionCreate() {

         $model = new Activity();
         $modelsSection = [new ActivitySection()];
         $modelsMember[] = [new ActivitySectionMember];
         $role = Yii::$app->user->identity->role;
         $tahun = date('Y');
         $bulan = date('m');
         $tanggal = date('d');

         if ($model->load(Yii::$app->request->post())) {

             $post = Yii::$app->request->post();
             $model->role = Yii::$app->user->identity->role;
             if ($role == 4) {
               $model->finance_status = 1;
               $model->department_status = 1;
               $model->chief_status = 1;
             } elseif ($role == 8) {
               $id_user = Yii::$app->user->identity->id;
               $sectionId = \common\models\Section::find()->where(['user_id' => $id_user])->one();
               $depId = \common\models\Department::find()->where(['id' => $sectionId->id_depart])->one();
               $chiefId = \common\models\Chief::find()->where(['id' => $depId->id_chief])->one();
               $model->finance_status = 0;
               $model->department_status = 0;
               $model->chief_status = 0;
               $model->department_code_id = $depId->id;
               $model->chief_code_id = $chiefId->id;
             }

             $model->done = 0;
             $model->date_start = $post['from_date'];
             $model->date_end = $post['to_date'];

             if ($role == 4) {
                $data = SecretariatBudget::findOne($post['source_sdm']);
                if ($data == null) {
                  Yii::$app->getSession()->setFlash('danger', 'Jenis SDM / Kode Anggaran Harus Diisi');
                  return $this->redirect(Yii::$app->request->referrer);
                 } else {
                    if ($post['source_value'] > $data->secretariat_budget_value ) {
                    Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                    return $this->redirect(Yii::$app->request->referrer);
                   }
                 }


             } elseif ($role == 8) {
                $data = SectionBudget::findOne($post['source_sdm']);
                // var_dump($data);die;
                if ($data == null) {
                  Yii::$app->getSession()->setFlash('danger', 'Jenis SDM / Kode Anggaran Harus Diisi');
                  return $this->redirect(Yii::$app->request->referrer);
                } else {
                  if ($post['source_value'] > $data->section_budget_value ) {
                  Yii::$app->getSession()->setFlash('danger', 'Dana Yang Diajukan Melebihi Anggaran Saat Ini');
                  return $this->redirect(Yii::$app->request->referrer);
                  }
                }
             }

             if ($post['jenis_sdm_source']=='4') {
                 $data = SecretariatBudget::findOne($post['source_sdm']);
                 $data->secretariat_budget_value=$data->secretariat_budget_value-(float)$post['source_value'];
                 $data->save();
                 $idSekreBudget = $data->id;
             } else if ($post['jenis_sdm_source']=='8') {
                 $data = SectionBudget::findOne($post['source_sdm']);
                 $data->section_budget_value=$data->section_budget_value-(float)$post['source_value'];
                 $data->save();
                 $idSectionBudget = $data->id;
             }

             // get ActivitySection data from POST
             $modelsSection = Model::createMultiple(ActivitySection::classname());
             Model::loadMultiple($modelsSection, Yii::$app->request->post());

             // get ActivitySectionMember data from POST
             $loadsData =  Yii::$app->request->post();
             for ($i=0; $i<count($modelsSection); $i++) {
                 $loadsData['ActivitySectionMember'] =  Yii::$app->request->post()['ActivitySectionMember'][$i];
                 $modelsMember[$i] = Model::createMultiple(ActivitySectionMember::classname(),[] ,$loadsData);
                 Model::loadMultiple($modelsMember[$i], $loadsData);
             }

             // validate all models - see example online for ajax validation
             // https://github.com/wbraganca/yii2-dynamicform
             $valid = $model->validate();

             // save deposit data
             if ($valid) {
                 if ($this->saveDeposit($model,$modelsSection,$modelsMember)) {

                   if($post){
                     if($post['ketua']){
                       $modelsMain = new ActivityMainMember;
                       $modelsMain->name_committee = "Ketua";
                       $modelsMain->name_member = $post['ketua'];
                       $modelsMain->activity_id = $model->id;
                       // var_dump("test");die;

                       $modelsMain->save();
                     }
                     if($post['wakil']){
                       $modelsMain = new ActivityMainMember;
                       $modelsMain->name_committee = "Wakil";
                       $modelsMain->name_member = $post['wakil'];
                       $modelsMain->activity_id = $model->id;
                       // var_dump("test");die;

                       $modelsMain->save();
                     }
                     if($post['sekretaris']){
                       $modelsMain = new ActivityMainMember;
                       $modelsMain->name_committee = "Sekretaris";
                       $modelsMain->name_member = $post['sekretaris'];
                       $modelsMain->activity_id = $model->id;
                       // var_dump("test");die;

                       $modelsMain->save();
                     }
                     if($post['bendahara']){
                       $modelsMain = new ActivityMainMember;
                       $modelsMain->name_committee = "Bendahara";
                       $modelsMain->name_member = $post['bendahara'];
                       $modelsMain->activity_id = $model->id;
                       // var_dump("test");die;

                       $modelsMain->save();
                     }
                   }

                       if ($role == 4) {
                          //save activity code
                           $model->activity_code = '01'.$model->id.''.$tahun.''.$bulan;
                           $model->save(false);

                           $sekreBudget = new ActivityBudgetSecretariat();
                           $sekreBudget->secretariat_budget_id = $idSekreBudget;
                           $sekreBudget->budget_value_sum = $post['source_value'];

                           $sekreBudget->activity_id = $model->id;
                           $sekreBudget->save(false);
                       } elseif ($role == 8) {
                           //save activity code
                           $model->activity_code = '01'.$model->id.''.$tahun.''.$bulan;
                           $model->save(false);

                           $sectionBudget = new ActivityBudgetSection();
                           $sectionBudget->section_budget_id = $idSectionBudget;
                           $sectionBudget->budget_value_sum = $post['source_value'];

                           $sectionBudget->activity_id = $model->id;
                           $sectionBudget->save(false);
                       }
                       Yii::$app->getSession()->setFlash('success', 'Buat Data Kegiatan Berhasil');
                       return $this->redirect('index');
                 }
             }
         }

         return $this->render('_form', [
             'model' => $model,
             'modelsSection'  => (empty($modelsSection)) ? [new ActivitySection] : $modelsSection,
             'modelsMember' => (empty($modelsMember)) ? [new ActivitySectionMember] : $modelsMember,
         ]);
     }

    /**
     * Updates an existing Activity model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
     public function actionUpdate($id) {

        $role = Yii::$app->user->identity->role;

         // retrieve existing Deposit data
         $model = Activity::find()->where(['id'=>$id])->one();

         $ketua = ActivityMainMember::find()
                    ->where(['activity_id'=>$id])
                    ->andWhere(['name_committee'=>"Ketua"])->one();
         $wakil = ActivityMainMember::find()
                    ->where(['activity_id'=>$id])
                    ->andWhere(['name_committee'=>"Wakil"])
                    ->one();
         $sekretaris = ActivityMainMember::find()
                    ->where(['activity_id'=>$id])
                    ->andWhere(['name_committee'=>"Sekretaris"])
                    ->one();
         $bendahara = ActivityMainMember::find()
                    ->where(['activity_id'=>$id])
                    ->andWhere(['name_committee'=>"Bendahara"])
                    ->one();

          if ($role == 4) {
            $budget = ActivityBudgetSecretariat::find()->where(['activity_id'=>$model])->one();
            $baru = SecretariatBudget::find()->where(['id'=>$budget->secretariat_budget_id])->one();
            $reject = ActivityReject::find()->where(['activity_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
            $range = $model->date_start.' to '.$model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_sum;
            $oldBudget = $baru->secretariat_budget_value;
          } elseif ($role == 8) {
            $budget = ActivityBudgetSection::find()->where(['activity_id'=>$model->id])->one();
            $baru = SectionBudget::find()->where(['id'=>$budget->section_budget_id])->one();
            $reject = ActivityReject::find()->where(['activity_id'=>$model->id])->orderBy(['id'=>SORT_DESC])->one();
            $range = $model->date_start.' to '.$model->date_end;
            $range_start = $model->date_start;
            $range_end = $model->date_end;
            $oldDP = $budget->budget_value_sum;
            $oldBudget = $baru->section_budget_value;
          }


         // retrieve existing ActivitySection data
         $oldActivitySectionIds = ActivitySection::find()->select('id')
             ->where(['activity_id' => $id])->asArray()->all();
         $oldActivitySectionIds = ArrayHelper::getColumn($oldActivitySectionIds,'id');
         $modelsSection = ActivitySection::findAll(['id' => $oldActivitySectionIds]);
         $modelsSection = (empty($modelsSection)) ? [new ActivitySection] : $modelsSection;

         // retrieve existing Loads data
         $oldLoadIds = [];
         foreach ($modelsSection as $i => $modelSection) {
             $oldLoads = ActivitySectionMember::findAll(['section_activity_id' => $modelSection->id]);
             $modelsMember[$i] = $oldLoads;
             $oldLoadIds = array_merge($oldLoadIds,ArrayHelper::getColumn($oldLoads,'id'));
             $modelsMember[$i] = (empty($modelsMember[$i])) ? [new ActivitySectionMember] : $modelsMember[$i];
         }

         // handle POST
         if ($model->load(Yii::$app->request->post())) {


             $post = Yii::$app->request->post();
             $model->role = Yii::$app->user->identity->role;
             if ($role == 4) {
               $model->finance_status = 1;
               $model->department_status = 1;
               $model->chief_status = 1;
             } elseif ($role == 8) {
               $model->finance_status = 0;
               $model->department_status = 0;
               $model->chief_status = 0;
             }
             $model->done = 0;
             $model->date_start = $post['from_date'];
             $model->date_end = $post['to_date'];

             // get ActivitySection data from POST
             $modelsSection = Model::createMultiple(ActivitySection::classname(), $modelsSection);
             Model::loadMultiple($modelsSection, Yii::$app->request->post());
             $newActivitySectionIds = ArrayHelper::getColumn($modelsSection,'id');

             // get ActivitySectionMember data from POST
             $newLoadIds = [];
             $loadsData =  Yii::$app->request->post();
             for ($i=0; $i<count($modelsSection); $i++) {
                 $loadsData['ActivitySectionMember'] =  Yii::$app->request->post()['ActivitySectionMember'][$i];
                 $modelsMember[$i] = Model::createMultiple(ActivitySectionMember::classname(),$modelsMember[$i] ,$loadsData);
                 Model::loadMultiple($modelsMember[$i], $loadsData);
                 $newLoadIds = array_merge($newLoadIds,ArrayHelper::getColumn($loadsData['ActivitySectionMember'],'id'));
             }

             // delete removed data
             $delLoadIds = array_diff($oldLoadIds,$newLoadIds);
             if (! empty($delLoadIds)) ActivitySectionMember::deleteAll(['id' => $delLoadIds]);
             $delActivitySectionIds = array_diff($oldActivitySectionIds,$newActivitySectionIds);
             if (! empty($delActivitySectionIds)) ActivitySection::deleteAll(['id' => $delActivitySectionIds]);

             // validate all models
             $valid = $model->validate();

             // save deposit data
             if ($valid) {
                 if ($this->saveDeposit($model,$modelsSection,$modelsMember) && $budget->load(Yii::$app->request->post())) {

                   if ($role == 4) {

                       // $dp = $budget->budget_value_dp;
                       $total = $budget->budget_value_sum;
                       $modal = $baru->secretariat_budget_value;


                       //nilai anggaran dp lebih kecil dari anggaran saat ini
                       if ($total <= $modal) {
                           $dpBaru = $oldDP - $total;
                           $oldBudgetBaru = $modal + $dpBaru;
                           if ($oldBudgetBaru <= 0) {
                               var_dump($oldBudgetBaru);die();
                               Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                               return $this->redirect(Yii::$app->request->referrer);
                           }
                       }

                       //nilai anggaran dp lebih besar dari anggaran saat ini
                       if ($total >= $modal) {
                           $dpBaru = $total - $oldDP;
                           $oldBudgetBaru = $modal - $dpBaru;
                           if ($oldBudgetBaru <= 0) {
                               var_dump($oldBudgetBaru);die();
                               Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                               return $this->redirect(Yii::$app->request->referrer);
                           }
                       }

                       // $budget->budget_value_dp = $budget->budget_value_dp;
                       $budget->budget_value_sum = $budget->budget_value_sum;
                       $budget->save(false);

                       $baru->secretariat_budget_value = $oldBudgetBaru;
                       $baru->save(false);

                   } elseif ($role == 8) {

                       // $dp = $budget->budget_value_dp;
                       $total = $budget->budget_value_sum;
                       $modal = $baru->section_budget_value;

                       // if ($dp > $total) {
                       //     Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Yang Diajukan');
                       //     return $this->redirect(Yii::$app->request->referrer);
                       // }

                       if ($total <= $modal) {
                           $dpBaru = $oldDP - $total;
                           $oldBudgetBaru = $modal + $dpBaru;
                           if ($oldBudgetBaru <= 0) {
                               var_dump($oldBudgetBaru);die();
                               Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                               return $this->redirect(Yii::$app->request->referrer);
                           }
                       }
                       //nilai anggaran dp lebih besar dari anggaran saat ini
                       if ($total >= $modal) {
                           $dpBaru = $total - $oldDP;
                           $oldBudgetBaru = $modal - $dpBaru;
                           if ($oldBudgetBaru <= 0) {
                               var_dump($oldBudgetBaru);die();
                               Yii::$app->getSession()->setFlash('danger', 'Tidak Bisa Melebihi Anggaran Dana Saat Ini');
                               return $this->redirect(Yii::$app->request->referrer);
                           }
                       }

                       // $budget->budget_value_dp = $budget->budget_value_dp;
                       $budget->budget_value_sum = $budget->budget_value_sum;
                       $budget->save(false);

                       $baru->section_budget_value = $oldBudgetBaru;
                       $baru->save(false);

                       if ($reject != null) {
                          $reject->delete();
                       } else {
                          //no-action
                       }
                   }

                   if($post){
                     if($post['ketua']){
                       $modelsMain = ActivityMainMember::find()->where(['activity_id'=>$id])->andWhere(['name_committee'=>'Ketua'])->one();
                       $modelsMain->name_member = $post['ketua'];
                       $modelsMain->save();
                     }
                     if($post['wakil']){
                       $modelsMain = ActivityMainMember::find()->where(['activity_id'=>$id])->andWhere(['name_committee'=>'Wakil'])->one();
                       $modelsMain->name_member = $post['wakil'];
                       $modelsMain->save();
                     }
                     if($post['sekretaris']){
                       $modelsMain = ActivityMainMember::find()->where(['activity_id'=>$id])->andWhere(['name_committee'=>'Sekretaris'])->one();
                       $modelsMain->name_member = $post['sekretaris'];
                       $modelsMain->save();
                     }
                     if($post['bendahara']){
                       $modelsMain = ActivityMainMember::find()->where(['activity_id'=>$id])->andWhere(['name_committee'=>'Bendahara'])->one();
                       $modelsMain->name_member = $post['bendahara'];
                       $modelsMain->save();
                     }
                   }
                     Yii::$app->getSession()->setFlash('success', 'Edit Data Kegiatan Berhasil');
                     return $this->redirect('index');
                 }
             }
         }

         // show VIEW
         return $this->render('_form-update', [
             'model' => $model,
             'budget' => $budget,
             'baru' => $baru,
             'ketua' => $ketua,
             'wakil' => $wakil,
             'bendahara' => $bendahara,
             'sekretaris' => $sekretaris,
             'modelsSection'  => $modelsSection,
             'modelsMember' => $modelsMember,
             'range' => $range,
             'range_start' => $range_start,
             'range_end' => $range_end,
         ]);
     }

    /**
     * Deletes an existing Activity model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    public function actionReport($id) {

        $role = Yii::$app->user->identity->role;

        if ($role == 4) {
          $model = Activity::find()->where(['id'=>$id])->one();
          $budget = ActivityBudgetSecretariat::find()->where(['activity_id'=>$model->id])->one();
          $baru = SecretariatBudget::find()->where(['id'=>$budget->secretariat_budget_id])->one();
          $kodeid = Secretariat::find()->where(['id'=>$baru->secretariat_id])->one();
          $section = ActivitySection::find()->where(['activity_id'=>$model->id])->all();
          $ketua = ActivityMainMember::find()->where(['name_committee'=>'Ketua'])->andWhere(['activity_id'=>$model->id])->one();
          $wakil = ActivityMainMember::find()->where(['name_committee'=>'Wakil'])->andWhere(['activity_id'=>$model->id])->one();
          $sekretaris = ActivityMainMember::find()->where(['name_committee'=>'Sekretaris'])->andWhere(['activity_id'=>$model->id])->one();
          $bendahara = ActivityMainMember::find()->where(['name_committee'=>'Bendahara'])->andWhere(['activity_id'=>$model->id])->one();

        } else if ($role == 8) {
          $model = Activity::find()->where(['id'=>$id])->one();
          $budget = ActivityBudgetSection::find()->where(['activity_id'=>$model->id])->one();
          $baru = SectionBudget::find()->where(['id'=>$budget->section_budget_id])->one();
          $kodeid = Section::find()->where(['id'=>$baru->section_id])->one();
          $section = ActivitySection::find()->where(['activity_id'=>$model->id])->all();
          $ketua = ActivityMainMember::find()->where(['name_committee'=>'Ketua'])->andWhere(['activity_id'=>$model->id])->one();
          $wakil = ActivityMainMember::find()->where(['name_committee'=>'Wakil'])->andWhere(['activity_id'=>$model->id])->one();
          $sekretaris = ActivityMainMember::find()->where(['name_committee'=>'Sekretaris'])->andWhere(['activity_id'=>$model->id])->one();
          $bendahara = ActivityMainMember::find()->where(['name_committee'=>'Bendahara'])->andWhere(['activity_id'=>$model->id])->one();
        }

        $content = $this->renderPartial('view_pdf',[
            'model'=>$model,
            'budget'=>$budget,
            'baru'=>$baru,
            'section'=>$section,
            'ketua'=>$ketua,
            'wakil'=>$wakil,
            'sekretaris'=>$sekretaris,
            'bendahara'=>$bendahara,
            'kodeid'=>$kodeid
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
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            // any css to be embedded if required
            'cssInline' => '.kv-heading-1{font-size:18px}',
             // call mPDF methods on the fly
            'methods' => [
                'SetFooter'=>['{PAGENO}'],
            ]
        ]);

    // return the pdf output as per the destination setting
    return $pdf->render();
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

    protected function saveDeposit($model,$modelsSection,$modelsMember) {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($go = $model->save(false)) {

                // loop through each payment
                foreach ($modelsSection as $i => $modelSection) {
                    // save the payment record
                    $modelSection->activity_id = $model->id;
                    if ($go = $modelSection->save(false)) {
                        // loop through each load
                        foreach ($modelsMember[$i] as $ix => $modelMember) {
                            // save the load record
                            $modelMember->activity_id = $model->id;
                            $modelMember->section_activity_id = $modelSection->id;
                            if (! ($go = $modelMember->save(false))) {
                                $transaction->rollBack();
                                break;
                            }

                        }
                    }
                }
            }
            if ($go) {
                $transaction->commit();
            }
        } catch (Exception $e) {
            $transaction->rollBack();
        }
        return $go;
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
                $budgetSekre = ActivityDailyBudgetSecretariat::find()->where(['secretariat_budget_id'=>$data->id])->one();
                // $hasilDP = $budgetSekre->budget_value_sum - $budgetSekre->budget_value_dp;
                // $hasil =  ($hasilDP + $data->secretariat_budget_value) / 2;
                $datas['message']= "
                 <div class='col-sm-12'>
                    <div class='form-group'>
                        <label class='col-sm-4'>Nilai Anggaran Saat Ini</label>
                        <div class='col-sm-8'>
                            ".$hasil."
                        </div>
                    </div>
                </div>
                <br>
                <br>
                ";
                $datas['max']=$hasil;
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
