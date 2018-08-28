<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 21.08.18
 * Time: 0:17
 */

namespace shop\entities;

/**
 * Class Meta
 * @package shop\entities
 * @property string $title
 * @property string $description
 * @property string $keywords
 */

class Meta
{
    public $title;
    public $description;
    public $keywords;

    public function __construct($title, $description, $keywords)
    {
        $this->title=$title;
        $this->description=$description;
        $this->keywords=$keywords;
    }

}
