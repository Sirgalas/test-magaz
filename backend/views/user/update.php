<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
/* @var $this yii\web\View */
/* @var $user shop\entities\user\User */
/**@var $model shop\forms\manage\user\UserEditForm*/

$this->title = 'Update User: ' . $user->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->id, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">
    <?php $form=ActiveForm::begin() ?>
    <?= $form->field($model,'username')->textInput(['maxLength'=>true]);?>
    <?= $form->field($model,'email')->textInput(['maxLength'=>true]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
