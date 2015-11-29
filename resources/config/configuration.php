<?php

return [
    'access_key' => [
        'required' => true,
        'type'     => 'anomaly.field_type.encrypted'
    ],
    'secret_key' => [
        'required' => true,
        'type'     => 'anomaly.field_type.encrypted'
    ],
    'region'     => [
        'required' => true,
        'type'     => 'anomaly.field_type.select',
        'config'   => [
            'options' => [
                'us-east-1'      => 'US East (N. Virginia)',
                'us-west-1'      => 'US West (N. California)',
                'us-west-2'      => 'US West (Oregon)',
                'eu-central-1'   => 'EU (Frankfurt)',
                'eu-west-1'      => 'EU (Ireland)',
                'ap-northeast-1' => 'Asia Pacific (Tokyo)',
                'ap-southeast-1' => 'Asia Pacific (Singapore)',
                'ap-southeast-2' => 'Asia Pacific (Sydney)',
                'sa-east-1'      => 'South America (Sao Paulo)'
            ]
        ]
    ],
    'bucket'     => [
        'required' => true,
        'type'     => 'anomaly.field_type.text'
    ],
    'prefix'     => [
        'type'   => 'anomaly.field_type.boolean',
        'config' => [
            'default_value' => true
        ]
    ]
];
