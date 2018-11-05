<?php

namespace shop\services;


class TransactionManager
{
    public function warp(callable $function):void
    {
        \Yii::$app->db->transaction($function);
    }

}
