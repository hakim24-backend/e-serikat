<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Transfer';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="transfer-index">
	<?php
      if(Yii::$app->user->identity->role != '2' && Yii::$app->user->identity->role != '3'){ ?>
					<p>
			        <?= Html::a('Unggah Excel', ['create'], ['class' => 'btn btn-success']) ?>
			    </p>
	<?php }
?>
	<div class="c-content-tab-1 c-theme c-margin-t-30">
        <div class="clearfix">
            <ul class="nav nav-tabs c-font-uppercase c-font-bold">
                <li class="active">
                    <a href="#tab_1_1_content" data-toggle="tab">Dana Sekretariat</a>
                </li>
                <li>
                    <a href="#tab_1_2_content" data-toggle="tab">Dana Ketua</a>
                </li>
                <li>
                    <a href="#tab_1_3_content" data-toggle="tab">Dana Departemen</a>
                </li>
                <li>
                    <a href="#tab_1_4_content" data-toggle="tab">Dana Seksi</a>
                </li>
            </ul>
        </div>
        <div class="box box-primary">
            <div class="box-body">
                <div class="tab-content c-bordered c-padding-lg">
                    <div class="tab-pane active" id="tab_1_1_content">
                        <div class="table-responsive">
                            <?php Pjax::begin(); ?>
                            <?=
                                GridView::widget([
                                    'dataProvider' => $budgetSecretariat,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'header' => 'Kode Sekeratriat',
                                            'attribute' => 'secretariat.secretariat_code',
                                        ],
                                        [
                                            'header' => 'Nama Sekeratriat',
                                            'attribute' => 'secretariat.secretariat_name',
                                        ],
                                        [
                                            'header' => 'Sumber Dana',
                                            'attribute' => 'secretariatBudget.budget_code',
                                        ],
                                        [
                                            'header' => 'Kode Anggaran',
                                            'attribute' => 'secretariat_budget_code',
                                        ],
                                        [
                                            'header' => 'Nilai Anggaran',
                                            'attribute' => 'secretariat_budget_value',
                                        ],
                                        // [
                                        //     'class' => 'yii\grid\ActionColumn',
                                        //     'header' => 'Action',
                                        //     'template' => '{update}',
                                        //     'buttons' => [
                                        //         'update' => function ($url,$model,$key) {

                                        //             $url = Url::toRoute(['/transfer/update-sekretariat', 'id' => $model->id]);
                                        //             return Html::a(
                                        //                 '<span class="glyphicon glyphicon-pencil"></span>',
                                        //                 $url,
                                        //                 [
                                        //                     'title' => 'Update Anggaran',
                                        //                 ]
                                        //             );


                                        //         },
                                        //     ]
                                        // ],
                                    ],
                                ]);
                            ?>
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_1_2_content">
                        <div class="table-responsive">
                            <?php Pjax::begin(); ?>
                            <?=
                                GridView::widget([
                                    'dataProvider' => $budgetChief,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'header' => 'Kode Ketua',
                                            'attribute' => 'chief.chief_code',
                                        ],
                                        [
                                            'header' => 'Nama Ketua',
                                            'attribute' => 'chief.chief_name',
                                        ],
                                        [
                                            'header' => 'Sumber Dana',
                                            'attribute' => 'chiefBudget.budget_code',
                                        ],
                                        [
                                            'header' => 'Kode Anggaran',
                                            'attribute' => 'chief_budget_code',
                                        ],
                                        [
                                            'header' => 'Nilai Anggaran',
                                            'attribute' => 'chief_budget_value',
                                        ],
                                        // [
                                        //     'class' => 'yii\grid\ActionColumn',
                                        //     'header' => 'Action',
                                        //     'template' => '{update}',
                                        //     'buttons' => [
                                        //         'update' => function ($url,$model,$key) {

                                        //             $url = Url::toRoute(['/transfer/update-ketua', 'id' => $model->id]);
                                        //             return Html::a(
                                        //                 '<span class="glyphicon glyphicon-pencil"></span>',
                                        //                 $url,
                                        //                 [
                                        //                     'title' => 'Update Anggaran',
                                        //                 ]
                                        //             );


                                        //         },
                                        //     ]
                                        // ],
                                    ],
                                ]);
                            ?>
                            <?php Pjax::end(); ?>
                        </div>

                    </div>
                    <div class="tab-pane" id="tab_1_3_content">
                        <div class="table-responsive">
                            <?php Pjax::begin(); ?>
                            <?=
                                GridView::widget([
                                    'dataProvider' => $budgetDepartment,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'header' => 'Kode Sekeratriat',
                                            'attribute' => 'department.depart_code',
                                        ],
                                        [
                                            'header' => 'Nama Sekeratriat',
                                            'attribute' => 'department.depart_name',
                                        ],
                                        [
                                            'header' => 'Sumber Dana',
                                            'attribute' => 'departmentBudget.budget_code',
                                        ],
                                        [
                                            'header' => 'Kode Anggaran',
                                            'attribute' => 'department_budget_code',
                                        ],
                                        [
                                            'header' => 'Nilai Anggaran',
                                            'attribute' => 'department_budget_value',
                                        ],
                                        // [
                                        //     'class' => 'yii\grid\ActionColumn',
                                        //     'header' => 'Action',
                                        //     'template' => '{update}',
                                        //     'buttons' => [
                                        //         'update' => function ($url,$model,$key) {

                                        //             $url = Url::toRoute(['/transfer/update-departemen', 'id' => $model->id]);
                                        //             return Html::a(
                                        //                 '<span class="glyphicon glyphicon-pencil"></span>',
                                        //                 $url,
                                        //                 [
                                        //                     'title' => 'Update Anggaran',
                                        //                 ]
                                        //             );


                                        //         },
                                        //     ]
                                        // ],
                                    ],
                                ]);
                            ?>
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab_1_4_content">
                        <div class="table-responsive">
                            <?php Pjax::begin(); ?>
                            <?=
                                GridView::widget([
                                    'dataProvider' => $budgetSection,
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        [
                                            'header' => 'Kode Seksi',
                                            'attribute' => 'section.section_code',
                                        ],
                                        [
                                            'header' => 'Nama Seksi',
                                            'attribute' => 'section.section_name',
                                        ],
                                        [
                                            'header' => 'Sumber Dana',
                                            'attribute' => 'sectionBudget.budget_code',
                                        ],
                                        [
                                            'header' => 'Kode Anggaran',
                                            'attribute' => 'section_budget_code',
                                        ],
                                        [
                                            'header' => 'Nilai Anggaran',
                                            'attribute' => 'section_budget_value',
                                        ],
                                        // [
                                        //     'class' => 'yii\grid\ActionColumn',
                                        //     'header' => 'Action',
                                        //     'template' => '{update}',
                                        //     'buttons' => [
                                        //         'update' => function ($url,$model,$key) {

                                        //             $url = Url::toRoute(['/transfer/update-seksi', 'id' => $model->id]);
                                        //             return Html::a(
                                        //                 '<span class="glyphicon glyphicon-pencil"></span>',
                                        //                 $url,
                                        //                 [
                                        //                     'title' => 'Update Anggaran',
                                        //                 ]
                                        //             );


                                        //         },
                                        //     ]
                                        // ],
                                    ],
                                ]);
                            ?>
                            <?php Pjax::end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
