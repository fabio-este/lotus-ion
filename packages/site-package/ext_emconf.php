<?php

/**
 * Extension Manager/Repository config file for ext "site-package".
 */
$EM_CONF[$_EXTKEY] = [
    'title' => 'SitePackage',
    'description' => '',
    'category' => 'templates',
    'constraints' => [
        'depends' => [
            'bootstrap_package' => '13.0.0-14.9.99',
        ],
        'conflicts' => [
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'FabioEste\\SitePackage\\' => 'Classes',
        ],
    ],
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'clearCacheOnLoad' => 1,
    'author' => 'Fabio Stegmeyer',
    'author_email' => 'fabio.stegmeyer@gmail.com',
    'author_company' => 'Fabio Stegmeyer',
    'version' => '1.0.0',
];
