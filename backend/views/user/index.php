<?php

use yii\helpers\Html;
use yii\grid\GridView;
use shop\helpers\UserHelper;
use shop\entities\user\User;
use kartik\widgets\DatePicker;
/* @var $this yii\web\View */
/* @var $searchModel backend\search\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

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
                    [
                        'attribute'=>'created_at',
                        'filter'=>  DatePicker::widget([
                            'model' => $searchModel,
                            'attribute'=> 'date_from',
                            'attribute2' => 'date_to',
                            'type' => DatePicker::TYPE_RANGE,
                            'separator'=>'-',
                            'pluginOptions'   =>[
                               'todayHighlight' =>true,
                               'autoclose'=>true,
                               'format' => 'yyyy-mm-dd',
                            ],
                        ]),
                        'format'=>'datetime'
                    ],
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
                        'filter'=> UserHelper::statusList(),
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
