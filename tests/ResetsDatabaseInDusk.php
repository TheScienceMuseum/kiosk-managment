<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait ResetsDatabaseInDusk
{
    use DatabaseMigrations;

    protected function runDatabaseMigrations()
    {
        $this->artisan('migrate:fresh', [
            '--drop-views' => true,
        ]);

        $this->artisan('db:seed', []);
        $this->artisan('db:seed', [
            '--class' => 'TestDatabaseSeeder',
        ]);

        $this->app[Kernel::class]->setArtisan(null);

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');

            RefreshDatabaseState::$migrated = false;
        });
    }
}