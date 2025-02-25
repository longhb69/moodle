<?php

defined('MOODLE_INTERNAL') || die();

$capabilities = [
    'block/lesson_tracker:addinstance' => [
        'captype' => 'write',
        'contextlevel' => CONTEXT_COURSE,
        'archetypes' => [
            'editingteacher' => CAP_ALLOW,
            'manager' => CAP_ALLOW
        ],
    ],
];
