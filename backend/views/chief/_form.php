<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Chief */

$this->title = 'Create Chief';
$this->params['breadcrumbs'][] = ['label' => 'Chiefs', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id' => 'form-signup', 'enableClientValidation' => true,]); ?>

				<?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                </div>

<?php ActiveForm::end(); ?>