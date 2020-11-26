## Authentication support

### Installation

Please apply migrations for dektrium/yii2-user extension to add authentication support:
                           
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

You could update admin's password with the console command

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
            'customLayout' => '{yourLayout}'
        ],
        ...
     ],	        
     ...
];
```
If {yourLayout} is not defined, @app/views/layouts/main will be used as default. 

Configuration of usage dynamic routes is at section [dynamic routes](dynamic-routes.md) 

Full documentation for configuration of user submodule is available at [Dektrium project](https://github.com/dektrium/yii2-user/blob/master/docs/configuration.md).
  
### Usage
We can use links like:

* `yourhost/user/resend` Displays resend form
* `yourhost/user/login` Displays login form
* `yourhost/user/logout` Logs the user out (available only via POST method)
* `yourhost/user/settings/account` Displays account settings form (email, username, password)




