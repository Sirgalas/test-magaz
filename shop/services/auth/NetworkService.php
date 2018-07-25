<?php


namespace shop\services\auth;

use shop\entities\User\User;
use shop\repositories\UserRepository;

class NetworkService
{
    public $users;

    public function __construct(UserRepository $users)
    {
        $this->users=$users;
    }

    public function auth($network,$identity)
    {

        if($user= $this->users->findByNetworkIdentity($network,$identity))
            return $user;

        $user=User::networkSignup($network,$identity);
        $this->users->save($user);
        return $user;
    }
}