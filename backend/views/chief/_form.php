<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\Chief */

$this->title = 'Data Master Ketua';
$this->params['breadcrumbs'][] = ['label' => 'Ketua', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id' => 'form-signup', 'enableClientValidation' => true]); ?>

				<?= $form->field($model, 'name')->textInput(['autofocus' => true, 'required'=>true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Save', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
                    <a class="btn btn-danger" href="<?= Url::to(Yii::$app->request->referrer);?>">Batal</a>
                </div>

<?php ActiveForm::end(); ?>