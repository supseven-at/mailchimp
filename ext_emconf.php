<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Mailchimp subscription',
    'description' => 'Simple MailChimp integration to let users register to a specific list',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => 'mail@ringer.it',
    'state' => 'stable',
    'clearCacheOnLoad' => true,
    'version' => '4.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '8.7.5-9.5.99'
        ],
        'conflicts' => [],
        'suggests' => [
            'typoscript_rendering' => '2.2.0-2.99.99'
        ],
    ],
];
