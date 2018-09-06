<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 30.08.18
 * Time: 0:11
 */

namespace shop\entities\shop\queries;

use paulzi\nestedsets\NestedSetsQueryTrait;
use yii\db\ActiveQuery;

class CategoryQuery extends ActiveQuery
{
      use NestedSetsQueryTrait;
}
