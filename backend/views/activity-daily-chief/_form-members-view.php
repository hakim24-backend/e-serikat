<?php


use yii\helpers\Html;

use wbraganca\dynamicform\DynamicFormWidget;


?>


<?php DynamicFormWidget::begin([

    'widgetContainer' => 'dynamicform_inner',

    'widgetBody' => '.container-rooms',

    'widgetItem' => '.room-item',

    'limit' => 4,

    'min' => 1,

    'uniqueClass'=>'form-control-ui',

    'autocompleteDatasource'=>$list_seksi,

    'insertButton' => '.add-room',

    'deleteButton' => '.remove-room',

    'model' => $modelsMember[0],

    'formId' => 'dynamic-form',

    'formFields' => [

        'section_name_member'

    ],

]); ?>

<table class="table table-bordered">

    <thead>

        <tr>

            <th>Nama Anggota</th>


            </th>

        </tr>

    </thead>

    <tbody class="container-rooms">

    <?php foreach ($modelsMember as $indexMember => $modelMember): ?>

        <tr class="room-item">

            <td class="vcenter">

                <?php

                    // necessary for update action.

                    if (! $modelMember->isNewRecord) {

                        echo Html::activeHiddenInput($modelMember, "[{$indexSection}][{$indexMember}]id");

                    }

                ?>
                <?= $form->field($modelMember, "[{$indexSection}][{$indexMember}]section_name_member")->widget(\yii\jui\AutoComplete::classname(), [
                    'options' => [ 'class' => 'form-control form-control-ui', 'required' => true ,'disabled'=>true],
                ])->label(false) ?>

            </td>

        </tr>

     <?php endforeach; ?>

    </tbody>

</table>

<?php DynamicFormWidget::end(); ?>
