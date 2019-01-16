<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ketua';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chief-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Buat Akun', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            // 'id',
            'chief_name',
            'chief_code',
            'status_budget',
            'user_id',

            [   
                'class' => 'yii\grid\ActionColumn',
                'header' => 'Action',
                'template' => '| {edit} | {view} | {delete} |',
                'buttons' => [
                    

                    'edit' => function($url, $model, $key)
                    {
                        if ($model->user) {
                            $url = Url::toRoute(['/chief/update', 'id' => $model->id]);
                            return Html::a(
                                '<span class="glyphicon glyphicon-pencil"></span>',
                                $url, 
                                [
                                    'title' => 'Edit User',
                                ]
                            );
                        }
                        else{
                            $url = Url::toRoute(['/chief/create', 'id' => $model->id]);
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
</div>
