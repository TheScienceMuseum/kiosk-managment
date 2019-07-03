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
        $this->output->title('Building application-schema.json');

        $outputFilePath = base_path('resources/application-schema.json');

        $this->output->text('Building resources stack');

        $resources = [
            "kiosk" => [
                "label_key" => "name",
                "fields" => [
                    [
                        "name" => "identifier",
                        "label" => "Identifier",
                        "type" => "text",
                        "readonly" => true,
                        "help" => "The identifier used to set up the kiosk initially."
                    ],
                    [
                        "name" => "name",
                        "label" => "Name",
                        "type" => "text",
                        "filter" => true,
                        "sub_fields" => ["identifier"]
                    ],
                    [
                        "name" => "location",
                        "label" => "Location",
                        "type" => "text",
                        "filter" => true,
                    ],
                    [
                        "name" => "asset_tag",
                        "label" => "Asset Tag",
                        "type" => "text",
                        "filter" => true,
                    ],
                    [
                        "name" => "last_seen_at",
                        "label" => "Last Seen",
                        "type" => "time_ago",
                        "filter" => true,
                        "readonly" => true,
                    ],
                    [
                        "name" => "assigned_package_version",
                        "label" => "Assigned Package",
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
                        "label" => "Currently Running Package",
                        "help" => "This is the package that the kiosk last reported in use.",
                        "type" => "resource_instance",
                        "resource" => "package",
                        "id_key" => ["id"],
                        "label_key" => ["package.name", " version: ", "version"],
                        "readonly" => true,
                    ],
                    [
                        "name" => "logs",
                        "label" => "Kiosk Logs",
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
                    "label" => "Level",
                    "type" => "select",
                    "options" => [[
                        'label' => 'Error',
                        'value' => 'error',
                    ], [
                        'label' => 'Info',
                        'value' => 'info',
                    ]],
                    "readonly" => true,
                    "filter" => true,
                ], [
                    "name" => "message",
                    "label" => "Message",
                    "type" => "text",
                    "readonly" => true,
                ], [
                    "name" => "timestamp",
                    "label" => "Timestamp",
                    "type" => "time_stamp",
                    "readonly" => true,
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
                    "label" => "Name",
                    "type" => "text",
                    "filter" => true,
                    "readonly" => true,
                    "create_with" => true,
                ], [
                    "name" => "aspect_ratio",
                    "label" => "Aspect Ratio",
                    "type" => "select",
                    "options" => [[
                        "label" => "Landscape",
                        "value" => "16:9",
                    ], [
                        "label" => "Portrait",
                        "value" => "9:16",
                    ]],
                    "filter" => true,
                    "readonly" => true,
                    "create_with" => true,
                    "id_key" => "value",
                    "collapse_on_store" => true,
                ], [
                    "name" => "versions",
                    "label" => "Versions",
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
                            "path" => "/admin/packages/{package.id}",
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
                                "label" => "Select an approver",
                                "help" => "Choose a user to review your package.",
                                "type" => "resource_instance",
                                "resource" => "user",
                                "resource_filters" => [
                                    "roles" => ["content editor"],
                                ],
                                "default" => [
                                    "label" => "Not Needed",
                                    "value" => "",
                                ],
                                "nullable" => true,
                                "null_value_label" => "Not Needed",
                                "id_key" => ["id"],
                                "label_key" => ["name", "(", "email", ")"],
                                "collapse_on_store" => true,
                            ]],
                        ],
                    ], [
                        "label" => "Approve",
                        "action" => [
                            "resource" => "package_version",
                            "action" => "update",
                            "params" => [
                                "status" => "approved",
                            ],
                        ],
                        "post_action" => [
                            "path" => "/admin/packages/{package.id}",
                        ],
                        "display_condition" => [
                            "PERMISSION" => "publish all packages",
                            "status" => "pending",
                            "progress" => 100,
                        ],
                    ],[
                        "label" => "Deploy",
                        "action" => [
                            "resource" => "package_version",
                            "action" => "deploy",
                        ],
                        "display_condition" => [
                            "PERMISSION" => "deploy packages to all kiosks",
                            "status" => ["approved", "deployed"],
                        ],
                        "confirmation" => [
                            "text" => "Are you sure you want to deploy this package version to a kiosk?",
                            "yes" => "Go ahead",
                            "no" => "Cancel",
                            "choices" => [[
                                "name" => "kiosk",
                                "label" => "Select a Kiosk",
                                "help" => "Choose a kiosk to deploy your package to.",
                                "type" => "resource_instance",
                                "resource" => "kiosk",
                                "resource_filters" => [
                                    "registered" => true,
                                ],
                                "null_value_label" => "Not Needed",
                                "id_key" => ["id"],
                                "label_key" => ["name", "(", "identifier", ")"],
                                "collapse_on_store" => true,
                            ]],
                        ],
                        "post_action" => [
                            "path" => "/admin/packages/{package.id}",
                        ],
                    ], [
                        "label" => "Delete",
                        "action" => [
                            "resource" => "package_version",
                            "action" => "delete",
                        ],
                        "post_action" => [
                            "refresh" => true,
                        ],
                        "display_condition" => [[
                            "PERMISSION" => "edit all packages",
                            "status" => ["draft", "failed"],
                        ], [
                            "PERMISSION" => "deploy packages to all kiosks",
                            "status" => ["pending", "approved"],
                            "progress" => "100",
                        ]],
                        "confirmation" => [
                            "text" => "Are you sure you want to delete this version of the package?",
                            "yes" => "Go ahead",
                            "no" => "Cancel",
                        ],
                    ], [
                        "label" => "Edit",
                        "action" => [
                            "path" => "/editor/{package.id}/version/{id}",
                        ],
                        "display_condition" => [
                            "status" => ["draft", "failed"],
                        ],
                    ]]
                ]],
                "actions" => [
                    "index" => [
                        "verb" => "get",
                        "path" => "/api/package",
                        "pagination" => true,
                        "actions" => [
                            [
                                "label" => "Duplicate",
                                "action" => [
                                    "resource" => "package",
                                    "action" => "duplicate",
                                ],
                                "confirmation" => [
                                    "text" => "You are about to create a new packaged based on <span class='text-dark'>{name}</span>, the most recent version of <span class='text-dark'>{name}</span> will become the first version of this new package.",
                                    "yes" => "Go ahead",
                                    "no" => "Cancel",
                                    "choices" => [[
                                        "name" => "name",
                                        "default" => "",
                                        "help" => "Give a name to your new package.",
                                        "type" => "text",
                                    ]],
                                ],
                                "post_action" => [
                                    "path" => "/admin/packages/{id}",
                                ],
                            ],
                            [
                                "label" => "Delete",
                                "action" => [
                                    "resource" => "package",
                                    "action" => "delete",
                                ],
                                "confirmation" => [
                                    "text" => "You are about to delete the package <span class='text-dark'>{name}</span>, this will delete all versions of this package. Are you sure?",
                                    "yes" => "Go ahead",
                                    "no" => "Cancel",
                                ],
                                "post_action" => [
                                    "path" => "/admin/packages",
                                ],
                                "display_condition" => [
                                    "kiosks" => "0-LENGTH",
                                ],
                            ],
                        ],
                    ],
                    "show" => [
                        "verb" => "get",
                        "path" => "/api/package/{id}",
                        "actions" => [
                            [
                                "label" => "Duplicate",
                                "action" => [
                                    "resource" => "package",
                                    "action" => "duplicate",
                                ],
                                "confirmation" => [
                                    "text" => "You are about to create a new packaged based on <span class='text-dark'>{name}</span>, the most recent version of <span class='text-dark'>{name}</span> will become the first version of this new package.",
                                    "yes" => "Go ahead",
                                    "no" => "Cancel",
                                    "choices" => [[
                                        "name" => "name",
                                        "default" => "",
                                        "help" => "Give a name to your new package.",
                                        "type" => "text",
                                    ]],
                                ],
                                "post_action" => [
                                    "path" => "/admin/packages/{id}",
                                ],
                            ],
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
                        "post_action" => [
                            "path" => "/editor/{id}/version/{versions[0].id}",
                        ],
                    ],
                    "duplicate" => [
                        "verb" => "post",
                        "path" => "/api/package/{id}",
                    ],
                    "delete" => [
                        "verb" => "delete",
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
                    "label" => "Version",
                    "type" => "text",
                    "readonly" => true,
                ], [
                    "name" => "created_at",
                    "label" => "Created",
                    "type" => "text",
                    "readonly" => true,
                ], [
                    "name" => "status",
                    "label" => "Status",
                    "type" => "text",
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
                        ], [
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
                        ], [
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
                    "deploy" => [
                        "verb" => "post",
                        "path" => "/api/package/{package.id}/version/{id}/deploy"
                    ],
                ],
            ],
            "user" => [
                "label_key" => "name",
                "fields" => [
                    [
                        "name" => "name",
                        "label" => "Name",
                        "type" => "text",
                        "help" => "The full name of the user.",
                        "filter" => true,
                        "required" => true,
                        "create_with" => true,
                    ], [
                        "name" => "email",
                        "label" => "Email",
                        "type" => "text",
                        "help" => "A valid email address for the user.",
                        "filter" => true,
                        "required" => true,
                        "readonly" => true,
                        "create_with" => true,
                    ], [
                        "name" => "roles",
                        "label" => "User Roles",
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
