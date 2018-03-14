## User support

### Installation

To add user support apply migrations for dektrium/yii2-user extension:
                           
```bash
php yii migrate/up --migrationPath=@vendor/dektrium/yii2-user/migrations
```

To add support for emcms pages apply migrations 

```bash
php yii migrate/up --migrationPath=@vendor/almeyda/yii2-emcms/migrations
```

To add 'admin' user apply console command

```bash
php yii user/create admin@example.com admin password
```

To update admin's password apply console command

```bash
php yii user/password admin password
```

### Configuration

Modify your application configuration to include:
```php
return [
    ...
    'modules' => [
            ...
            'emcms' => [
                'modules' => [
                    'user' => [
                        'class' => 'dektrium\user\Module',
                        'enableUnconfirmedLogin' => false,
                        'enableConfirmation' => false,
                        'enableRegistration' => false,
                        'enablePasswordRecovery' => true,
                        'confirmWithin' => 21600,
                        'admins' => ['admin'],
                        ...
                    ]
                ]
            ],
            ...
        ],...	        
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
                ...
                ...
            ]
        ...
        ],
        ...
    ],
    ...             
];
```
Full documentation for configuration of user submodule is at [Dektrium project](https://github.com/dektrium/yii2-user/blob/master/docs/configuration.md).
  
### Usage
We can use links like:

* `yourhost/user/register` Displays registration form
* `yourhost/user/resend` Displays resend form
* `yourhost/user/confirm` Confirms a user (requires id and token query params)
* `yourhost/user/login` Displays login form
* `yourhost/user/logout` Logs the user out (available only via POST method)
* `yourhost/user/request` Displays recovery request form
* `yourhost/user/reset` Displays password reset form (requires id and token query params)
* `yourhost/user/profile` Displays profile settings form
* `yourhost/user/account` Displays account settings form (email, username, password)
* `yourhost/user/networks` Displays social network accounts settings page
* `yourhost/user/show` Displays user's profile (requires id query param)
* `yourhost/user/index` Displays user management interface




