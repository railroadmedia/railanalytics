<?php

return array(
    // Settings example
    'local' => [
        'active-tracking-providers' => ['ga', 'gtm', 'gaw', 'fp', 'ga4', 'im'],
        'providers' => [
            'drumeo' = [
                'google-analytics' =>
                    [
                        'tracking-id' => 'XXX',
                        // https://support.google.com/optimize/answer/6262084
                        'optimise-id' => 'XXX' // if set to null, optimise code not be rendered
                    ],
                'google-analytics-v4' =>
                    [
                        'tracking-id' => 'XXX'

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
                        'utt-link' => '',
                        'sid' => '',
                        'auth-token' => '',
                        'campaign-id' => '',
                        'tag-action-tracker-id' => '',
                        'api-action-tracker-id' => '',
                        'sign-up-action-tracker-id' => ''
                    ]
            ],
            'pianote' => [
                'google-analytics' =>
                    [
                        'tracking-id' => 'XXX',
                        // https://support.google.com/optimize/answer/6262084
                        'optimise-id' => 'XXX' // if set to null, optimise code not be rendered
                    ],
                'google-analytics-v4' =>
                    [
                        'tracking-id' => 'XXX'

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
                        'utt-link' => '',
                        'sid' => '',
                        'auth-token' => '',
                        'campaign-id' => '',
                        'tag-action-tracker-id' => '',
                        'api-action-tracker-id' => '',
                        'sign-up-action-tracker-id' => ''
                    ]
            ],
            'guitareo' => [
                'google-analytics' =>
                    [
                        'tracking-id' => 'XXX',
                        // https://support.google.com/optimize/answer/6262084
                        'optimise-id' => 'XXX' // if set to null, optimise code not be rendered
                    ],
                'google-analytics-v4' =>
                    [
                        'tracking-id' => 'XXX'

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
                        'utt-link' => '',
                        'sid' => '',
                        'auth-token' => '',
                        'campaign-id' => '',
                        'tag-action-tracker-id' => '',
                        'api-action-tracker-id' => '',
                        'sign-up-action-tracker-id' => ''
                    ]
            ],
            'singeo' => [
                'google-analytics-v4' =>
                    [
                        'tracking-id' => 'XXX'

                    ],
                'impact' =>
                    [
                        'utt-link' => '',
                        'sid' => '',
                        'auth-token' => '',
                        'campaign-id' => '',
                        'tag-action-tracker-id' => '',
                        'api-action-tracker-id' => '',
                        'sign-up-action-tracker-id' => ''
                    ]
            ],

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