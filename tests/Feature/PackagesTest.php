<?php

namespace Tests\Feature\DraftFeatures;

use Tests\TestCase;
use Tests\ActsAs;

class PackagesTest extends TestCase
{
    use ActsAs;

    // The packager utility, which will take the package and create the zip (or similar) to be made available to the kiosks to pick up.
    //
    // Builds a static website form the content package and zips it. this can then be pulled by the kiosk.
    //
    // This is what happens when you publish:
    // admin hits publish
    // api to the package to start
    // package manager asks for data from CMS
    // CMS returns data (incl. assets and content etc., them settings)
    // REACT renders the pages. inserting variables into templates
    // Bundle them into files.
    // compress into zip.
    // upload to s3
    // update kiosk's entity entry
    // return completed response to CMS.

    public function test()
    {
        $this->assertTrue(true);
    }
}
