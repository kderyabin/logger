# PSR Logger Implementation

```php
return [
    'levelPriorityMin' => null,
    'levelPriorityMax' => null,
    'levelCode' => [
        LogLevel::EMERGENCY => LOG_EMERG,
        LogLevel::ALERT => LOG_ALERT,
        LogLevel::CRITICAL => LOG_CRIT,
        LogLevel::ERROR => LOG_ERR,
        LogLevel::WARNING => LOG_WARNING,
        LogLevel::NOTICE => LOG_NOTICE,
        LogLevel::INFO => LOG_INFO,
        LogLevel::DEBUG => LOG_DEBUG
    ],
    'message' => [
        'instance' => \Kod\Message::class,
        'fields' => [
            'remote_ip' => '',
            'req_id' => 'WhWobqwSABgAAAAUskEAAAAD',
            'session_id' => '36c5d42e8e802c5b45680aa6d56bd26f59cd507aee29eff43466d1929ee41fc0',
            'method' => 'post',
            'path' => '/',
            'service_name' => 'T.U.',
            'login' => '',
        ],
        'setters' => [
            'login' => function ($current) {
                return $current ? md5($current) : $current;
            }
        ],
        'dates' => [
            'datetime' =>'Y-m-d H:i:s.u',
        ],
        'filter' => [
            'filterNullValue' => function ($fields) {
                return array_filter($fields, function ($value) {
                    return $value !== null;
                });
            },
        ]
    ],
    'channels' => [
        [
            'handler' => [
                'instance' => \Kod\Handlers\StreamHandler::class,
                'options' => [
                    'path' => 'php://stdout'
                ],
            ],
            'formatter' => [
                'instance' => \Kod\Formatters\JsonFormatter::class,
                'options' => [
                    'json_encode' => \JSON_ERROR_NONE | \JSON_UNESCAPED_SLASHES | \JSON_PRETTY_PRINT
                ]
            ],

        ],
    ]
];
```

