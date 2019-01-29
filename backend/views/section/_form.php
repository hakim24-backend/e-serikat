<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $model common\models\Department */
/* @var $form yii\widgets\ActiveForm */

$this->title = 'Data Master Seksi';
$this->params['breadcrumbs'][] = ['label' => 'Seksi', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="department-form">

    <?php $form = ActiveForm::begin(['id' => 'form-signup', 'enableClientValidation' => true]); ?>

        <?= $form->field($model, 'name')->textInput(['autofocus' => true, 'required'=>true]) ?>

        <div class="form-group">
            <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
        </div>

    <?php ActiveForm::end(); ?>

</div>
