<?php

namespace Tests;

use App\User;

trait ActsAs
{
    protected $userDeveloper;
    protected $userAdmin;
    protected $userTechAdmin;
    protected $userContentAuthor;
    protected $userContentEditor;

    public function actingAsDeveloper()
    {
        return $this->actingAs(User::role('developer')->first());
    }
    public function actingAsAdmin()
    {
        return $this->actingAs(User::role('admin')->first());
    }
    public function actingAsTechAdmin()
    {
        return $this->actingAs(User::role('tech admin')->first());
    }
    public function actingAsContentAuthor()
    {
        return $this->actingAs(User::role('content author')->first());
    }
    public function actingAsContentEditor()
    {
        return $this->actingAs(User::role('content editor')->first());
    }

}