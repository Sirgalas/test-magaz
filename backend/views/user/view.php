<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use shop\helpers\UserHelper;
/* @var $this yii\web\View */
/* @var $model shop\entities\user\User */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <div class="box">
        <div class="box-body">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'id',
                'username',
                'email:email',
                [
                  'attribute'=>'status',
                  'value'=>UserHelper::statusLabel($model->status),
                  'format'=>'raw'
                ],
                'status',
                'created_at:datetime',
                'updated_at:datetime',
            ],
        ]) ?>
        </div>
    </div>
</div>
