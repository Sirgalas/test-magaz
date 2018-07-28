<?php


namespace shop\services\auth;

use shop\entities\user\User;
use shop\forms\SignupForm;
use yii\mail\MailerInterface;
use shop\repositories\UserRepository;

class SignUpService
{
    private $mailer;
    private $name;
    private $users;

    public function __construct($name,MailerInterface $mailer,UserRepository $userRepository) {

        $this->name=$name;
        $this->mailer=$mailer;
        $this->users=$userRepository;
    }

    public function signup(SignupForm $signupForm):User
    {
        $user=User::signup(
            $signupForm->username,
            $signupForm->email,
            $signupForm->password
        );
        $this->users->save($user);
        $sent=$this->mailer
            ->compose(
                ['html' => 'emailConfirmToken-html', 'text' => 'emailConfirmToken-text'],
                ['user'=>$user]
            )
            ->setTo($signupForm->email)
            ->setSubject('Signup confirm for ' . $this->name)
            ->send();
        if (!$sent)
            throw new \RuntimeException('Email sending error.');
    }

    public function confirm($token):void
    {
        if(empty($token))
            throw new  \DomainException('Empty confirm token.');

        $user=$this->users->getByEmailConfirm($token);

        $user->confirmEmail();

        $this->users->save($user);
    }

}