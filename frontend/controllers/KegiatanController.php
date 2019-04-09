<?php

namespace frontend\controllers;

use Yii;
use common\models\Activity;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\data\Pagination;

/**
 * Site controller
 */
class KegiatanController extends Controller {

    /**
     * @inheritdoc
     */

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionDetail($id) {
        $this->layout = 'article.php';
        $model = Activity::findOne($id);

        return $this->render('detail', [
            'model' => $model
        ]);
    }

}
