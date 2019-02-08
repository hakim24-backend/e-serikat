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

class BendaharaActivityDailyResponsibilityController extends \yii\web\Controller
{
    public function actionIndex()
    {
      	$dataProvider = new ActiveDataProvider([
        'query' => ActivityDaily::find()->where(['done'=> 1]),
        ]);
        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

}
