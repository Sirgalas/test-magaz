<?php

use yii\helpers\Html;
use yii\authclient\widgets\AuthChoice;

$this->title='Cabinet';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="cabinet-index">
    <h1><?= Html::encode($this->title); ?></h1>
    <p>Hello <?= Yii::$app->user->identity->username; ?></p>
    <?= AuthChoice::widget([
        'baseAuthUrl' => ['auth/network/auth']
    ]); ?>
</div>
