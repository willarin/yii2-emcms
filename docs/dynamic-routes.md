## Dynamic routes

Dynamic routes modify your application configuration to include:

```php
return [
    ...
    'components' => [
        ...
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'rules' => [
                'user' => [
                    'pattern' => '<module:user>/<controller:\w+>/<action:\w+>',
                    'route' => '<module>/<action>'
                ],
                ...
            ]
        ...
        ],
        ...
    ],
    ...             
];
```



