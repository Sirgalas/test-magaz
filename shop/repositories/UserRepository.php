<?php
namespace shop\repositories;
use http\Exception\RuntimeException;
use shop\repositories\NotFoundException;
use shop\entities\user\User;

class UserRepository
{

    public function findByUsernameOrEmail($value): ?User
    {
        return User::find()->andWhere(['or', ['username' => $value], ['email' => $value]])->one();
    }

    public function getByEmailConfirmToken($token): User
    {
        return $this->getBy(['email_confirm_token' => $token]);
    }

    public function getByEmail($email): User
    {
        return $this->getBy(['email' => $email]);
    }

    public function getByPasswordResetToken($token): User
    {
        return $this->getBy(['password_reset_token' => $token]);
    }

    public function existsByPasswordResetToken(string $token): bool
    {
        return (bool) User::findByPasswordResetToken($token);
    }

    public function save(User $user): void
    {
        if (!$user->save()) {
            throw new \RuntimeException(var_dump($user->errors));
        }
    }

    private function getBy(array $condition): User
    {
        if (!$user = User::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('User not found.');
        }
        return $user;
    }

    public function findByNetworkIdentity($network, $identity): ?User
    {
        return User::find()->joinWith('userNetworks n')->andWhere(['n.network'=>$network,'n.identity'=>$identity])->one();
    }

    public function get($id): User
    {
        return $this->getBy(['id'=>$id]);
    }

    public function remove(User $user)
    {
            if(!$user->delete())
                throw new \RuntimeException('Removing error.');
    }



}
