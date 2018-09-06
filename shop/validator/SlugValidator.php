<?php

namespace shop\validator;

use yii\validators\RegularExpressionValidator;

class SlugValidator  extends RegularExpressionValidator
{
    public $pattern = '#^[А-Яа-яA-za-z0-9_-]*$#s';
    public $message = 'Только цифры буквы и перенос и нижнее подчеркивание';
}
