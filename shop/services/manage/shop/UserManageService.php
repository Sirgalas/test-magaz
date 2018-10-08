<?php


namespace shop\services\manage\shop;


use shop\entities\user\User;
use shop\forms\manage\user\UserCreateForm;
use shop\repositories\UserRepository;
use shop\forms\manage\user\UserEditForm;


class UserManageService
{
    private $repository;

    public function __construct(UserRepository $repository)
    {
          $this->repository=$repository;
    }

    public function create(UserCreateForm $form)
    {
        $user=User::create(
             $form->username,
             $form->email,
             $form->password
        );

        $this->repository->save($user);
        return $user;
    }

    public function edit($id, UserEditForm $form):void
    {
        $user=$this->repository->get($id);
        $user->edit(
            $form->username,
            $form->email
        );
        $this->repository->save($user);
    }
    public function remove($id):void
    {
       $user=$this->repository->get($id);
       $this->repository->remove($user);
    }
}
