<?php

namespace Tests;


use App\User;

trait UsesUsers
{
    protected $userDeveloper;
    protected $userAdmin;
    protected $userTechAdmin;
    protected $userContentAuthor;
    protected $userContentEditor;

    public function setUpUsers()
    {
        $this->userDeveloper = User::role('developer')->first();
        $this->userAdmin = User::role('admin')->first();
        $this->userTechAdmin = User::role('tech admin')->first();
        $this->userContentAuthor = User::role('content author')->first();
        $this->userContentEditor = User::role('content editor')->first();
    }
}