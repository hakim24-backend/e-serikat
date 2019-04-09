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

    <?php Pjax::begin(); ?>

<!--     <p>
        <?= Html::a('Buat Akun', ['registrasi'], ['class' => 'btn btn-success']) ?>
    </p> -->

    <div class="box box-primary">
            <div class="box-body">
                <div class="tab-content c-bordered c-padding-lg">
                    <div class="tab-pane active" id="tab_1_1_content">
                        <div class="table-responsive">
                        <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'formatter' => ['class' => 'yii\i18n\Formatter','nullDisplay' => 'Belum Terisi'],
                        'options' => ['style' => 'text-alignt:center'],
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            // // 'id',
                            // 'name_role',
                            // 'usersOne.username',
                            [
                            'header' => 'Nama Role',
                            'attribute' => 'name_role',
                            ],

                            [
                            'header' => 'Kode Serikat',
                            'attribute' => 'id',
                            ],

                            [
                            'header' => 'Username',
                            'attribute' => 'usersOne.username',
                            ],

                            [
                                'class' => 'yii\grid\ActionColumn',
                                'header' => 'Action',
                                'template' => ' {edit}  {delete} ',
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
                                    },
                                    'delete' => function ($url, $model) {
                                      if($model->users){
                                        $url = Url::toRoute(['/serikatinti/delete', 'id' => $model->id]);
                                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                                          'title' => Yii::t('app', 'Delete'),
                                          'data-confirm' => Yii::t('yii', 'Are you sure you want to delete?'),
                                          'data-method' => 'post', 'data-pjax' => '0',

                                        ]);
                                      }
                                    },
                                ]
                            ],
                        ],
                    ]); ?>
                    <?php Pjax::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
