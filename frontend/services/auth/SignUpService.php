<?php


namespace frontend\services\auth;

use common\entities\User;
use frontend\forms\SignupForm;
use yii\mail\MailerInterface;

class SignUpService
{
    private $mailer;
    private $name;

    public function __construct($name,MailerInterface $mailer) {

        $this->name=$name;
        $this->mailer=$mailer;
    }

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
        $sent=$this->mailer
            ->compose(
                ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-text'],
                ['user'=>$user]
            )
            ->setTo($signupForm->email)
            ->setSubject('Signup confirm for ' . $this->name)
            ->send();
        if (!$sent) {
            throw new \RuntimeException('Email sending error.');
        }
    }

    public function confirm($token):void
    {
        if(empty($token))
            throw new  \DomainException('Empty confirm token.');

        $user=User::findOne(['email_confirm_signup'=>$token]);

        if(!$user)
            throw new \DomainException('user not find');
        $user->confirmEmail();

        if(!$user->save())
            throw new \DomainException('Saving error');
    }

}