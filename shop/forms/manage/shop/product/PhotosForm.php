<?php


namespace shop\forms\manage\shop\product;

use yii\base\Model;
use yii\web\UploadedFile;

class PhotosForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $files;

    public function rules():array
    {
        return[
            ['files', 'each', 'rule' => ['image']],
        ];
    }

    public function beforeValidate():bool
    {
        if(parent::beforeValidate()){
            $this->files=UploadedFile::getInstance($this, 'files');
            return true;
        }
        return false;
    }

}
