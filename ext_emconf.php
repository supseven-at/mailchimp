<?php

$EM_CONF[$_EXTKEY] = array(
    'title' => 'Mailchimp subscription',
    'description' => '',
    'category' => 'plugin',
    'author' => 'Georg Ringer',
    'author_email' => 'g.ringer@supseven.at',
    'state' => 'stable',
    'internal' => '',
    'uploadfolder' => '1',
    'createDirs' => '',
    'clearCacheOnLoad' => 0,
    'version' => '1.0.0',
    'constraints' => array(
        'depends' => array(
            'typo3' => '6.2.0-7.6.99'
        ),
        'conflicts' => array(),
        'suggests' => array(
            'typoscript_rendering' => '1.0.5-1.99.999'
        ),
    ),
);