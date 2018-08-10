<?php

namespace shop\services\auth;

use shop\entities\user\User;
use shop\forms\auth\LoginForm;
use shop\repositories\UserRepository;

class AuthService
{

    private $users;

    public function __construct(UserRepository $userRepository) {
        $this->users=$userRepository;
    }

    public function auth(LoginForm $form): User
    {
        $user=$this->users->findByUsernameOrEmail($form->username);
        if(!$user||!$user->isActive()||!$user->validatePassword($form->password))
            throw new \DomainException('Undefined user or password.');
        return $user;

    }
}
