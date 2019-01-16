<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Ketua';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="department-index">

    <h1><?= Html::encode($this->title) ?></h1>


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
                'template' => '| {highligt} |',
                'buttons' => [
                    

                    'highligt' => function($url, $model, $key)
                    {
                        if ($model->user) {
                            $url = Url::toRoute(['/department/highlight', 'id' => $model->id]);
                            return Html::a(
                                '<span class="glyphicon glyphicon-th-list"></span>',
                                $url, 
                                [
                                    'title' => 'Higlight ',
                                ]
                            );
                        }
                        // else{
                        //     $url = Url::toRoute(['/department/view', 'id' => $model->id]);
                        //     return Html::a(
                        //         '<span class="glyphicon glyphicon-plus"></span>',
                        //         $url, 
                        //         [
                        //             'title' => 'View Data',
                        //         ]
                        //     );
                        // }
                    }
                ]
            ],
        ],
    ]); ?>
</div>
