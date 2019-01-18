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
use yii\helpers\ArrayHelper;

class RelokasiController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = new User;
        $item = ArrayHelper::map(Role::find()->all(), 'id', 'name_role');
        return $this->render('index', [
        	'item' => $item,
            'model' => $model,
        ]);
    }

}
