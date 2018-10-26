<?php

namespace Tests;

use App\User;

trait ActsAs
{
    public function actingAsDeveloper()
    {
        return $this->actingAs(User::role('developer')->first(), 'api');
    }
    public function actingAsAdmin()
    {
        return $this->actingAs(User::role('admin')->first(), 'api');
    }
    public function actingAsTechAdmin()
    {
        return $this->actingAs(User::role('tech admin')->first(), 'api');
    }
    public function actingAsContentAuthor()
    {
        return $this->actingAs(User::role('content author')->first(), 'api');
    }
    public function actingAsContentEditor()
    {
        return $this->actingAs(User::role('content editor')->first(), 'api');
    }

}