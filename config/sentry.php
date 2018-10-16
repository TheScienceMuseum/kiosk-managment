<?php

return array(
    'dsn' => 'https://40fc54ec694443148443b00b3130975a@sentry.io/1302070',

    // capture release as git sha
    'release' => trim(exec('git log --pretty="%h" -n1 HEAD')),

    // Capture bindings on SQL queries
    'breadcrumbs.sql_bindings' => true,

    // Capture default user context
    'user_context' => false,
);
