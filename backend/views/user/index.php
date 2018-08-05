<?php

use yii\helpers\Html;
use yii\grid\GridView;
use shop\helpers\UserHelper;
use shop\entities\user\User;

/* @var $this yii\web\View */
/* @var $searchModel backend\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="box">
        <div class="box-body">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],

                    'id',
                    'created_at:datetime',
                    [
                     'attribute'=>'username',
                     'format'=> 'raw',
                     'value'=> function(User $model){
                        return HTML::a(Html::encode($model->username),['view','id'=>$model->id]);
                     }
                    ],

                    'email:email',
                    [
                        'attribute'=>'status',
                        'value'=>function(User $model){
                            return UserHelper::statusLabel($model->status);
                        },
                        'format'=>'raw'
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>


</div>
