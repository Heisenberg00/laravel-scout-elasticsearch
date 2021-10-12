return [

    'connection' => [

        /*
         * Define your Elasticsearch connection here.
         */

        'hosts' => [
            [
                'host' => env('SCOUT_ELASTICSEARCH_HOST', '127.0.0.1'),
                'port' => env('SCOUT_ELASTICSEARCH_PORT', 9200),
                'scheme' => env('SCOUT_ELASTICSEARCH_SCHEME', null),
                'user' => env('SCOUT_ELASTICSEARCH_USER', null),
                'pass' => env('SCOUT_ELASTICSEARCH_PASSWORD', null),

                'aws_enable' => env('SCOUT_ELASTICSCOUT_AWS_ENABLED', false),
                'aws_region' => env('SCOUT_ELASTICSCOUT_AWS_REGION', 'us-east-1'),
                'aws_key' => env('AWS_ACCESS_KEY_ID', ''),
                'aws_secret' => env('AWS_SECRET_ACCESS_KEY', ''),
            ],
        ],
    ]
];
