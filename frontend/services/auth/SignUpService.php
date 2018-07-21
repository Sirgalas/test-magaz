<?php


namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\SignupForm;

class SignUpService
{
    public function signup(SignupForm $signupForm):User
    {
        $user=User::signup(
            $signupForm->username,
            $signupForm->email,
            $signupForm->password
        );
        if(!$user->save()){
            throw new \RuntimeException('Saving error');
        }
        return $user;
    }

}