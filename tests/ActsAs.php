<?php

namespace Tests;

use App\Kiosk;
use App\User;

trait ActsAs
{
    protected $kioskIdentifier;

    public function actingAsDeveloper() : TestCase
    {
        return $this->actingAs(User::role('developer')->first(), 'api');
    }
    public function actingAsAdmin() : TestCase
    {
        return $this->actingAs(User::role('admin')->first(), 'api');
    }
    public function actingAsTechAdmin() : TestCase
    {
        return $this->actingAs(User::role('tech admin')->first(), 'api');
    }
    public function actingAsContentAuthor() : TestCase
    {
        return $this->actingAs(User::role('content author')->first(), 'api');
    }
    public function actingAsContentEditor() : TestCase
    {
        return $this->actingAs(User::role('content editor')->first(), 'api');
    }
    public function actingAsRegisteredKiosk() : TestCase
    {
        $this->kioskIdentifier = Kiosk::first()->identifier;

        return $this;
    }

    public function actingAsUnregisteredKiosk() : TestCase
    {
        $this->kioskIdentifier = 'test-kiosk-unregistered';

        return $this;
    }
}