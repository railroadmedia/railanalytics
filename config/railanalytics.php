<?php

return array(
    // Settings example
    'local' => [
        'active-tracking-providers' => ['ga', 'gtm', 'gaw', 'fp', 'im'],
        'providers' => [
            'google-analytics' =>
                [
                    'tracking-id' => 'XXX',

                    // https://support.google.com/optimize/answer/6262084
                    'optimise-id' => 'XXX' // if set to null, optimise code not be rendered
                ],
            'google-tag-manager' =>
                [
                    'tracking-id' => 'XXX'
                ],
            'google-adwords' =>
                [
                    'google-conversion-id' => 'XXX',
                    'google-conversion-language' => 'en',
                    'google-conversion-format' => '3',
                    'google-conversion-color' => 'ffffff',
                    'google-conversion-label' => 'XXX',
                ],
            'facebook-pixel' =>
                [
                    'pixel-id' => 'XXX'
                ],
            'impact' =>
                [
                    'campaign-id' => '',
                    'utt-link' => ''
                ]
        ]
    ],

    // Production
    /*
    'production' => [
        'active-tracking-providers' => [...],

        'providers' => [...]
    ]
    */

    // Etc
);