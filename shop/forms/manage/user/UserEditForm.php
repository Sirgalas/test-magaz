<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 10.08.18
 * Time: 20:15
 */

namespace shop\forms\manage\user;

/**
 * Class UserEditForm
 * @package forms\manage\user
 * @property string $username
 * @property string email
 */

use yii\base\Model;
use shop\entities\user\User;

class UserEditForm  extends Model
{

    public $username;
    public $email;

    public function __construct(User $user, $config = [])
    {
        $this->username=$user->username;
        $this->email=$user->email;
        $this->_user=$user;
        parent::__construct($config);
    }

    public function rules():array
    {
       return [
           [['username','email'],'required'],
           ['email','email'],
           ['username','string','max'=>255],
           [['username','email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
           ];
    }
}
