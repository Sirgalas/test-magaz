<?php

namespace shop\services\auth;


use shop\forms\auth\PasswordResetRequestForm;
use shop\repositories\UserRepository;
use shop\forms\auth\ResetPasswordForm;
use Yii;
use yii\mail\MailerInterface;


class PasswordRessetFormSevice
{

    private $mailer;
    private $users;

    public function __construct( MailerInterface $mailer,UserRepository $users) {
        $this->mailer=$mailer;
        $this->users=$users;
    }

    public function request(PasswordResetRequestForm $form):void
    {
       $user=$this->users->getByEmail($form->email);

       if(!$user->isActive())
           throw new \DomainException('user not active');

        $user->requestPasswordReset();

        $this->users->save($user);

        $sent =$this->mailer
            ->compose(
                ['html' => 'passwordResetToken-html', 'text' => 'passwordResetToken-text'],
                ['user' => $user]
            )
            ->setTo($form->email)
            ->setSubject('Password reset for ' . Yii::$app->name)
            ->send();

        if(!$sent)
            throw new \RuntimeException('Sending error.');

    }

    public function validateToken($token):void
    {
        if(empty($token)||!is_string($token))
            throw new \DomainException('Password reset token cannot be blank.');

        if(!$this->users->existsByPasswordResetToken($token))
            throw new \DomainException('Wrong password reset token.');
    }

    public function reset(string $token, ResetPasswordForm $form){
        $user = $this->users->getByPasswordResetToken($token);
        $user->resetPassword($form->password);
        $this->users->save($user);

    }


}
