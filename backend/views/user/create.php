<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model shop\entities\user\User */

$this->title = 'Create User';
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">
    <?php $form=ActiveForm::begin(); ?>
    <?php $form->field($model,'username')->textInput(['maxLength'=>true]); ?>
    <?php $form->field($model,'email')->textInput(['maxLength'=>true]); ?>
    <?php $form->field($model,'password')->textInput(['maxLength'=>true]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>



</div>
