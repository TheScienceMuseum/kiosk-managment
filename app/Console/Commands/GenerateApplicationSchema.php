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
                "label_key" => "name",
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
                        "resource" => "package_versions",
                        "resource_filters" => [
                            "status" => "approved",
                        ],
                        "id_key" => ["id"],
                        "label_key" => ["package.name", " version: ", "version"],
                        "collapse_on_store" => true,
                    ],
                    [
                        "name" => "current_package_version",
                        "help" => "This is the package that the kiosk last reported in use.",
                        "type" => "resource_instance",
                        "resource" => "package",
                        "id_key" => ["id"],
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
                "label_key" => "name",
                "fields" => [[
                    "name" => "name",
                    "type" => "text",
                    "filter" => true,
                    "readonly" => true,
                    "create_with" => true,
                ],[
                    "name" => "versions",
                    "type" => "resource_collection",
                    "readonly" => true,
                    "resource" => "package_version",
                    "link_to_resource" => true,
                    "link_insert" => "versions",
                    "actions" => [[
                        "label" => "Submit for Approval",
                        "action" => [
                            "resource" => "package_version",
                            "action" => "update",
                            "params" => [
                                "status" => "pending",
                            ],
                        ],
                        "post_action" => [
                            "resource" => "package",
                            "action" => "show",
                        ],
                        "display_condition" => [
                            "status" => "draft",
                        ],
                        "confirmation" => [
                            "text" => "Are you sure you want to submit this package version for approval? No changes can be made after this action.",
                            "yes" => "Go ahead",
                            "no" => "Cancel",
                            "choices" => [[
                                "name" => "approval",
                                "help" => "Choose a user to review your package.",
                                "type" => "resource_instance",
                                "resource" => "user",
                                "resource_filters" => [
                                    "roles" => ["content editor"],
                                ],
                                "id_key" => ["id"],
                                "label_key" => ["name", "(", "email", ")"],
                                "collapse_on_store" => true,
                            ]],
                        ],
                    ],[
                        "label" => "Approve",
                        "action" => [
                            "resource" => "package_version",
                            "action" => "update",
                            "params" => [
                                "status" => "approved",
                            ],
                        ],
                        "post_action" => [
                            "resource" => "package",
                            "action" => "show",
                        ],
                        "display_condition" => [
                            "PERMISSION" => "publish all packages",
                            "status" => "pending",
                            "progress" => 100,
                        ],
//                    ],[
//                        "label" => "Deploy",
//                        "action" => [
//                            "path" => "/editor/{package.id}/version/{id}",
//                        ],
//                        "display_condition" => [
//                            "PERMISSION" => "deploy packages to all kiosks",
//                            "status" => "approved",
//                        ],
                    ],[
                        "label" => "Delete",
                        "action" => [
                            "resource" => "package_version",
                            "action" => "delete",
                        ],
                        "post_action" => [
                            "resource" => "package",
                            "action" => "show",
                        ],
                        "display_condition" => [[
                            "PERMISSION" => "edit all packages",
                            "status" => "draft",
                        ],[
                            "PERMISSION" => "deploy packages to all kiosks",
                            "status" => ["pending","approved"],
                            "progress" => "100",
                        ]],
                        "confirmation" => [
                            "text" => "Are you sure you want to delete this version of the package?",
                            "yes" => "Go ahead",
                            "no" => "Cancel",
                        ],
                    ],[
                        "label" => "Edit",
                        "action" => [
                            "path" => "/editor/{package.id}/version/{id}",
                        ],
                        "display_condition" => [
                            "status" => "draft",
                        ],
                    ]]
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
                        "actions" => [
                            [
                                "label" => "Create New Version",
                                "action" => [
                                    "resource" => "package_version",
                                    "action" => "store",
                                ],
                                "post_action" => [
                                    "path" => "/editor/{package.id}/version/{id}",
                                ],
                                "confirmation" => [
                                    "text" => "Create a new package version? (if there is a current draft version you may want to edit this instead)",
                                    "yes" => "Create new version",
                                    "no" => "Cancel",
                                ],
                            ],
                        ],
                    ],
                    "store" => [
                        "verb" => "post",
                        "path" => "/api/package",
                    ],
                    "update" => [
                        "verb" => "put",
                        "path" => "/api/package/{id}",
                    ],
                ],
            ],
            "package_versions" => [
                "label_key" => "version",
                "actions" => [
                    "index" => [
                        "verb" => "get",
                        "path" => "/api/package/versions",
                        "pagination" => true,
                    ],
                ],
            ],
            "package_version" => [
                "label_key" => "version",
                "fields" => [[
                    "name" => "version",
                    "type" => "text",
                    "filter" => true,
                    "readonly" => true,
                ], [
                    "name" => "created_at",
                    "type" => "text",
                    "filter" => true,
                    "readonly" => true,
                ], [
                    "name" => "status",
                    "type" => "text",
                    "filter" => true,
                    "readonly" => true,
                ]],
                "actions" => [
                    "index" => [
                        "verb" => "get",
                        "path" => "/api/package/{id}/version",
                        "pagination" => true,
                    ],
                    "store" => [
                        "verb" => "post",
                        "path" => "/api/package/{id}/version",
                    ],
                    "show" => [
                        "verb" => "get",
                        "path" => "/api/package/{package.id}/version/{id}",
                        "actions" => [[
                            "label" => "Submit for Approval",
                            "action" => [
                                "resource" => "package_version",
                                "action" => "update",
                                "params" => [
                                    "status" => "pending",
                                ],
                            ],
                            "post_action" => [
                                "resource" => "package_version",
                                "action" => "show",
                            ],
                            "display_condition" => [
                                "status" => "draft",
                            ],
                            "confirmation" => [
                                "text" => "Are you sure you want to submit this package version for approval? No changes can be made after this action.",
                                "yes" => "Go ahead",
                                "no" => "Cancel",
                                "choices" => [[
                                    "name" => "approval",
                                    "help" => "Choose a user to review your package.",
                                    "type" => "resource_instance",
                                    "resource" => "user",
                                    "resource_filters" => [
                                        "roles" => ["content editor"],
                                    ],
                                    "id_key" => ["id"],
                                    "label_key" => ["name", "(", "email", ")"],
                                    "collapse_on_store" => true,
                                ]],
                            ],
                        ],[
                            "label" => "Approve Package",
                            "action" => [
                                "resource" => "package_version",
                                "action" => "update",
                                "params" => [
                                    "status" => "approved",
                                ],
                            ],
                            "post_action" => [
                                "resource" => "package_version",
                                "action" => "show",
                            ],
                            "display_condition" => [
                                "status" => "pending",
                                "PERMISSION" => "publish all packages",
                            ],
                            "confirmation" => [
                                "text" => "Are you sure you want to approve this package version? No changes can be made after this action.",
                                "yes" => "Go ahead",
                                "no" => "Cancel",
                            ],
                        ],[
                            "label" => "View in Package Editor",
                            "action" => [
                                "path" => "/editor/{package.id}/version/{id}",
                            ],
                        ]],
                    ],
                    "update" => [
                        "verb" => "put",
                        "path" => "/api/package/{package.id}/version/{id}",
                    ],
                    "delete" => [
                        "verb" => "delete",
                        "path" => "/api/package/{package.id}/version/{id}",
                    ],
                ],
            ],
            "user" => [
                "label_key" => "name",
                "fields" => [
                    [
                        "name" => "name",
                        "type" => "text",
                        "help" => "The full name of the user.",
                        "filter" => true,
                        "required" => true,
                        "create_with" => true,
                    ], [
                        "name" => "email",
                        "type" => "text",
                        "help" => "A valid email address for the user.",
                        "filter" => true,
                        "required" => true,
                        "readonly" => true,
                        "create_with" => true,
                    ], [
                        "name" => "roles",
                        "type" => "select",
                        "resource" => "user_role",
                        "multiple" => true,
                        "filter" => true,
                        "collapse_on_store" => true,
                        "help" => "The permissions associated with the user.",
                        "required" => true,
                        "create_with" => true,
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
                                "action" => [
                                    "resource" => "user",
                                    "action" => "destroy",
                                ],
                                "post_action" => [
                                    "resource" => "user",
                                    "action" => "show",
                                ],
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
                                "action" => [
                                    "resource" => "user",
                                    "action" => "restore",
                                ],
                                "post_action" => [
                                    "resource" => "user",
                                    "action" => "show",
                                ],
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
                                "action" => [
                                    "resource" => "user",
                                    "action" => "onboard",
                                ],
                                "post_action" => [
                                    "resource" => "user",
                                    "action" => "show",
                                ],
                                "display_condition" => [
                                    "deleted_at" => false,
                                ],
                                "confirmation" => [
                                    "text" => "Are you sure you want to reset this accounts authentication details? (password and second factor will be reset)",
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
