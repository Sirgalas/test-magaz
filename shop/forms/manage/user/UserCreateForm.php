<?php

namespace shop\forms\manage\user;

use yii\base\Model;
use shop\entities\user\User;
/**
 *@property string $username;
 * @property string $email
 * @property string password
 */

class UserCreateForm
{
   public $username;
   public $email;
   public $password;

   public function rules():array
   {
       return[
           [['username','email'],'require' ],
           [['email', 'email']],
           [['username', 'email'], 'string', 'max' => 255],
           [['username', 'email'], 'unique', 'targetClass' => User::class],
           ['password', 'string', 'min' => 6],
       ];
   }
}
