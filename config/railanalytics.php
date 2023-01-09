<?php

return array(
    // Middleware group
    'blank_tracking_page_middleware_group_name' => 'my_middlware_group',

    // This is used to match the brand key values to the current domains so the right brand is served for a request.
    'brand_domains' => [
        'drumeo.com' => 'drumeo',
        'pianote.com' => 'pianote',
        'guitareo.com' => 'guitareo',
        'singeo.com' => 'singeo',
        'musora.com' => 'musora',
        'brand_1.com' => 'brand_1',
        'brand_2.com' => 'brand_2',
    ],

    // Settings format:
    // brand => [environment => [active-tracking-providers, providers => [provider-name=1 => [key1 => value1, key2 => value2], provider-name-2 => [key1 => value1, key2 => value2]]]]

    // Settings example
    'drumeo' => [
        'local' => [
            'active-tracking-providers' => ['ga', 'gtm', 'gaw', 'fp', 'ga4', 'im'],
            'providers' => [
                'google-analytics' => [
                    'tracking-id' => 'XXX',
                    // https://support.google.com/optimize/answer/6262084
                    'optimise-id' => 'XXX', // if set to null, optimise code not be rendered
                ],
                'google-analytics-v4' => [
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
                    ],
            ],
        ],
        'beta-testing' => [
            'active-tracking-providers' => ['ga', 'gtm', 'gaw', 'fp', 'ga4', 'im'],
            'providers' => [
                'google-analytics' => [
                    'tracking-id' => 'XXX',
                    // https://support.google.com/optimize/answer/6262084
                    'optimise-id' => 'XXX', // if set to null, optimise code not be rendered
                ],
                'google-analytics-v4' => [
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
                    ],
            ],
        ],
        'production' => [
            'active-tracking-providers' => ['ga', 'gtm', 'gaw', 'fp', 'ga4', 'im'],
            'providers' => [
                'google-analytics' => [
                    'tracking-id' => 'XXX',
                    // https://support.google.com/optimize/answer/6262084
                    'optimise-id' => 'XXX', // if set to null, optimise code not be rendered
                ],
                'google-analytics-v4' => [
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
                    ],
            ],
        ],
    ],

    'pianote' => [
        'local' => [
            'active-tracking-providers' => ['ga', 'gtm', 'gaw', 'fp', 'ga4', 'im'],
            'providers' => [
                'google-analytics' => [
                    'tracking-id' => 'XXX',
                    // https://support.google.com/optimize/answer/6262084
                    'optimise-id' => 'XXX', // if set to null, optimise code not be rendered
                ],
                'google-analytics-v4' => [
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
                    ],
            ],
        ],
    ],

    'brand_1' => [
        'local' => [
            'active-tracking-providers' => ['ga', 'gtm', 'gaw', 'fp', 'ga4', 'im'],
            'providers' => [
                'google-analytics' => [
                    'tracking-id' => 'XXX',
                    // https://support.google.com/optimize/answer/6262084
                    'optimise-id' => 'XXX', // if set to null, optimise code not be rendered
                ],
                'google-analytics-v4' => [
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
                    ],
            ],
        ],
    ],

    // Etc
);
