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

    public function buildResourceFromModel()
    {

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->output->title('Building application-schema.json');

        $outputFilePath = base_path('resources/application-schema.json');

        $this->output->text('Building resources stack');

        $resources = [
            "kiosk" => [
                "fields" => [
                    [
                        "name" => "identifier",
                        "type" => "text",
                        "readonly" => true,
                        "help" => "The identifier used to set up the kiosk initially."
                    ],
                    [
                        "name" => "name",
                        "type" => "text",
                        "filter" => true,
                        "sub_fields" => ["identifier"]
                    ],
                    [
                        "name" => "location",
                        "type" => "text",
                        "filter" => true,
                    ],
                    [
                        "name" => "asset_tag",
                        "type" => "text",
                        "filter" => true,
                    ],
                    [
                        "name" => "last_seen_at",
                        "type" => "time_ago",
                        "filter" => true,
                        "readonly" => true,
                    ],
                    [
                        "name" => "assigned_package_version",
                        "help" => "This is the package that is assigned to the kiosk.",
                        "type" => "resource_instance",
                        "nullable" => true,
                        "resource" => "package",
                        "id_key" => ["versions", "id"],
                        "label_key" => ["package.name", " version: ", "version"],
                        "collapse_on_store" => true,
                    ],
                    [
                        "name" => "current_package_version",
                        "help" => "This is the package that the kiosk last reported in use.",
                        "type" => "resource_instance",
                        "resource" => "package",
                        "id_key" => ["versions", "id"],
                        "label_key" => ["package.name", " version: ", "version"],
                        "readonly" => true,
                    ],
                    [
                        "name" => "logs",
                        "type" => "resource_collection",
                        "label_key" => ["level", " triggered at ", "timestamp"],
                        "resource" => "kiosk_logs",
                        "readonly" => true,
                    ],
                ],
                "actions" => [
                    "index" => [
                        "path" => "/api/kiosk",
                        "verb" => "get",
                        "pagination" => true,
                        "views" => [
                            [
                                "name" => "Unregistered",
                                "params" => [
                                    "filter[registered]" => false,
                                ],
                                "color" => "info",
                            ],
                            [
                                "name" => "Registered",
                                "params" => [
                                    "filter[registered]" => true,
                                ],
                                "color" => "info",
                            ],
                        ]
                    ],
                    "show" => [
                        "verb" => "get",
                        "path" => "/api/kiosk/{id}",
                    ],
                    "update" => [
                        "verb" => "put",
                        "path" => "/api/kiosk/{id}",
                    ],
                ],
            ],
            "kiosk_logs" => [
                "fields" => [[
                    "name" => "level",
                    "type" => "log_level",
                    "readonly" => true,
                    "filter" => true,
                ], [
                    "name" => "message",
                    "type" => "text",
                    "readonly" => true,
                    "filter" => true,
                ], [
                    "name" => "timestamp",
                    "type" => "time_stamp",
                    "readonly" => true,
                    "filter" => true,
                ]],
                "actions" => [
                    "index" => [
                        "path" => "/api/kiosk/{id}/logs",
                        "verb" => "get",
                        "pagination" => true,
                    ],
                ],
            ],
            "package" => [
                "fields" => [[
                    "name" => "name",
                    "type" => "text",
                    "filter" => true,
                ], [
                    "name" => "versions",
                    "type" => "resource_collection",
                ]],
                "actions" => [
                    "index" => [
                        "verb" => "get",
                        "path" => "/api/package",
                        "pagination" => true,
                    ],
                    "show" => [
                        "verb" => "get",
                        "path" => "/api/package/{id}",
                    ],
                ],
            ],
            "user" => [
                "fields" => [
                    [
                        "name" => "name",
                        "type" => "text",
                        "help" => "The full name of the user.",
                        "filter" => true,
                        "required" => true,
                    ], [
                        "name" => "email",
                        "type" => "text",
                        "help" => "A valid email address for the user.",
                        "filter" => true,
                        "required" => true,
                    ], [
                        "name" => "roles",
                        "type" => "select",
                        "resource" => "user_role",
                        "multiple" => true,
                        "filter" => true,
                        "collapse_on_store" => true,
                        "help" => "The permissions associated with the user.",
                        "required" => true,
                        "id_key" => ["name"],
                        "label_key" => ["name"],
                    ],
                ],
                "actions" => [
                    "index" => [
                        "verb" => "get",
                        "path" => "/api/user",
                        "pagination" => true,
                    ],
                    "show" => [
                        "verb" => "get",
                        "path" => "/api/user/{id}",
                        "actions" => [
                            [
                                "label" => "Suspend Account",
                                "action" => "destroy",
                                "post_action" => "show",
                                "display_condition" => [
                                    "deleted_at" => false,
                                ],
                                "confirmation" => [
                                    "text" => "Are you sure you want to suspend this account?",
                                    "yes" => "Go ahead",
                                    "no" => "Cancel",
                                ],
                            ],
                            [
                                "label" => "Restore Account",
                                "action" => "restore",
                                "post_action" => "show",
                                "display_condition" => [
                                    "deleted_at" => true,
                                ],
                                "confirmation" => [
                                    "text" => "Are you sure you want to restore this account?",
                                    "yes" => "Go ahead",
                                    "no" => "Cancel",
                                ],
                            ],
                            [
                                "label" => "Reset Authentication",
                                "action" => "onboard",
                                "display_condition" => [
                                    "deleted_at" => false,
                                ],
                                "confirmation" => [
                                    "text" => "Are you sure you want to reset this accounts authentication details?",
                                    "yes" => "Reset and Email User",
                                    "no" => "Cancel",
                                ],
                            ],
                        ]
                    ],
                    "store" => [
                        "verb" => "post",
                        "path" => "/api/user",
                    ],
                    "update" => [
                        "verb" => "put",
                        "path" => "/api/user/{id}",
                    ],
                    "destroy" => [
                        "verb" => "delete",
                        "path" => "/api/user/{id}",
                    ],
                    "restore" => [
                        "verb" => "post",
                        "path" => "/api/user/{id}/restore",
                    ],
                    "onboard" => [
                        "verb" => "post",
                        "path" => "/api/user/{id}/on-board",
                    ],
                ]
            ],
            "user_role" => [
                "fields" => [
                    "name" => [
                        "type" => "text",
                        "filter" => true,
                    ]
                ],
                "actions" => [
                    "index" => [
                        "verb" => "get",
                        "path" => "/api/user/role",
                    ],
                ],
            ],
        ];

        /**
         * Generate the available translations for the Trans helper
         */
        $this->output->text('Building language translations stack');
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

        $this->output->success('Built application-schema.json');
    }
}
