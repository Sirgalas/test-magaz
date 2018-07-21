<?php

namespace frontend\services\auth;


use frontend\forms\PasswordResetRequestForm;
use common\entities\User;
use frontend\forms\ResetPasswordForm;
use Yii;
use yii\mail\MailerInterface;


class PasswordRessetFormSevice
{

    private $mailer;

    public function __construct( MailerInterface $mailer) {
        $this->mailer=$mailer;
    }

    public function request(PasswordResetRequestForm $form):void
    {
        $user = User::findOne([
            'status' => User::STATUS_ACTIVE,
            'email' => $form->email,
        ]);

        if (!$user)
            throw new \DomainException('User not find');

        $user->requestPasswordReset();

        if(!$user->save())
            throw new \RuntimeException('User not save');

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

        if(User::findByPasswordResetToken($token))
            throw new \DomainException('Wrong password reset token.');
    }

    public function reset(string $token, ResetPasswordForm $form){
        $user=User::findByPasswordResetToken($token);

        if(!$user)
            throw new \DomainException('User is not found.');

        $user->resetPassword($form->password);

        if(!$user->save())
            throw new \DomainException('Saving error.');

    }
}