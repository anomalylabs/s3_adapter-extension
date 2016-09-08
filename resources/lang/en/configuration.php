<?php

return [
    'access_key' => [
        'label'        => 'Access Key',
        'instructions' => 'Enter your S3 access key.',
    ],
    'secret_key' => [
        'label'        => 'Secret Key',
        'instructions' => 'Enter your S3 secret key.',
    ],
    'region'     => [
        'label'        => 'Region',
        'instructions' => 'Choose the region your S3 bucket resides in.',
    ],
    'bucket'     => [
        'label'        => 'Bucket',
        'instructions' => 'Enter your S3 bucket name.',
    ],
    'prefix'     => [
        'label'        => 'Use disk slug as prefix?',
        'instructions' => 'If disabled, the bucket\'s root directory will be used.',
        'warning'      => 'Disabling may cause file collisions if the same bucket is used for multiple disks.',
    ],
];
