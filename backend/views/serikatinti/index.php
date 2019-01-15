<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'E-Serikat';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="serikatinti-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php Pjax::begin(); ?>

<!--     <p>
        <?= Html::a('Buat Akun', ['registrasi'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'name_role',

            [
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '| {edit} | {view} | {delete} |',
                'buttons' => [
                    

                    'edit' => function($url, $model, $key)
                    {
                        if ($model->users) {
                            $url = Url::toRoute(['/serikatinti/update', 'id' => $model->id]);
                            return Html::a(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                $url, 
                                [
                                    'title' => 'Edit User',
                                ]
                            );
                        }
                        else{
                            $url = Url::toRoute(['/serikatinti/create', 'id' => $model->id]);
                            return Html::a(
                                '<span class="glyphicon glyphicon-plus"></span>',
                                $url, 
                                [
                                    'title' => 'Buat User',
                                ]
                            );
                        }
                    }
                ]
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>
