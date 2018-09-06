<?php

namespace repositories\shop;

use http\Exception\RuntimeException;
use shop\entities\shop\Categories;
use shop\entities\shop\Characteristic;
use shop\repositories\NotFoundException;

class CharacteristicRepository
{
    public function get($id): Characteristic
    {
        if(!$caracteristic=Characteristic::findOne($id))
            throw new NotFoundException("Characteristic not find");
        return $caracteristic;
    }

    public function save(Characteristic $characteristic):void
    {
        if(!$characteristic->save())
            throw new \RuntimeException('Characteristic not save');
    }

    public function remove(Characteristic $characteristic):void
    {
        if(!$characteristic->delete())
            throw new \RuntimeException('Cahracteristic not remove');
    }

}
