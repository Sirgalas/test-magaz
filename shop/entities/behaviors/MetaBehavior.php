<?php

namespace shop\entities\behaviors;

use shop\entities\Meta;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\Json;

class MetaBehavior extends Behavior
{

    public $attribute = 'meta';
    public $jsonAttribute = 'meta_json';

    public function events()
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND=>'onAfterFind',
            ActiveRecord::EVENT_AFTER_INSERT=>'onAfterInsert',
            ActiveRecord::EVENT_AFTER_UPDATE=>'onAfterUpdate'
        ];
    }

    public function onAfterFind(Event $event)
    {
        $model=$event->sender;
        $meta=Json::decode($model->getAttribute($this->jsonAttribute));
        $model->{$this->attribute}=new Meta($meta['title'],$meta['description'],$meta['keywords']);
    }

    public function onAfterSave(Event $event){
        $model=$event->sender;
        $model->setAttribute('meta_json',[
            "title"=>$model->{$this->attribute}->title,
            "description"=>$model->{$this->attribute}->description,
            "keywords"=>$model->{$this->attribute}->keywords
        ]);
    }

}
