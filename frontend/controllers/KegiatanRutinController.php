<?php

namespace frontend\controllers;

use Yii;
use common\models\ActivityDaily;
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
class KegiatanRutinController extends Controller {

    /**
     * @inheritdoc
     */

    public function beforeAction($action) {
        $this->enableCsrfValidation = false;
        return parent::beforeAction($action);
    }

    public function actionDetail($id) {
        $this->layout = 'article.php';
        $model = ActivityDaily::findOne($id);

        return $this->render('detail', [
            'model' => $model
        ]);
    }

}
