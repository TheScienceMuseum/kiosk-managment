<?php

namespace Tests;


use App\User;

trait CreatesUsers
{
    public function createUserDeveloper($data)
    {
        return $this->createUser($data, 'developer');
    }

    public function createUserAdmin($data)
    {
        return $this->createUser($data, 'admin');
    }

    public function createUserTechAdmin($data)
    {
        return $this->createUser($data, 'tech admin');
    }

    public function createUserContentEditor($data)
    {
        return $this->createUser($data, 'content editor');
    }

    public function createUserContentAuthor($data)
    {
        return $this->createUser($data, 'content author');
    }

    private function createUser($data, $role)
    {
        $user = factory(User::class)->create($data);
        $user->assignRole($role);

        return $user;
    }
}