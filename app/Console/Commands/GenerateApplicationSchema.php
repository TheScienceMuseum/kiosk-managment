<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateApplicationSchema extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dev:generate-application-schema';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A dev helper that generates the application-schema.json file for use in front end assets.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $outputFilePath = base_path('resources/application-schema.json');

        /**
         * Generate the available resources for the Api helper
         */
        $resourcePaginationParams = ["page" => ["number" => "integer", "size" => "integer"]];

        $resources = [
            "user_role" => [
                "index" => [
                    "verb" => "get",
                    "path" => "/api/user/role",
                    "params" => array_merge([
                        "filter" => [
                            "name" => "string",
                        ]
                    ], $resourcePaginationParams),
                ],
            ],
            "user" => [
                "index" => [
                    "verb" => "get",
                    "path" => "/api/user",
                    "params" => array_merge([
                        "filter" => [
                            "name" => "string",
                            "email" => "string",
                            "role" => "string",
                        ]
                    ], $resourcePaginationParams),
                ],
                "store" => [
                    "verb" => "post",
                    "path" => "/api/user",
                ],
                "show" => [
                    "verb" => "get",
                    "path" => "/api/user/{id}",
                ],
                "update" => [
                    "verb" => "put",
                    "path" => "/api/user/{id}",
                ],
                "destroy" => [
                    "verb" => "delete",
                    "path" => "/api/user/{id}",
                ],
            ],
            "kiosk" => [
                "index" => [
                    "verb" => "get",
                    "path" => "/api/kiosk",
                    "params" => array_merge([
                        "filter" => [
                            "name" => "string",
                            "registered" => "boolean",
                        ],
                    ], $resourcePaginationParams),
                ],
                "show" => [
                    "verb" => "get",
                    "path" => "/api/kiosk/{id}",
                ],
                "update" => [
                    "verb" => "put",
                    "path" => "/api/kiosk/{id}",
                ],
                "destroy" => [
                    "verb" => "delete",
                    "path" => "/api/kiosk/{id}",
                ],
            ],
        ];

        /**
         * Generate the available translations for the Trans helper
         */
        $languages = [];
        $localeDirectories = File::directories(resource_path() . '/lang/');

        foreach ($localeDirectories as $directory) {
            $lang_files = File::files($directory);
            $trans = [];
            foreach ($lang_files as $f) {
                $filename = pathinfo($f)['filename'];
                $trans[$filename] = trans($filename);
            }

            $locale = last(explode('/', $directory));

            $languages[$locale] = $trans;
        }

        file_put_contents($outputFilePath, json_encode([
            'resources' => $resources,
            'language' => $languages,
        ]));
    }
}
