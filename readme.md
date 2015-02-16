Emcms Module
============

Emcms Module is a simple client-side CMS module for Yii2. 

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
$ php composer.phar require almeyda/yii2-emcms "dev-master"
```

or add

```
"almeyda/yii2-emcms": "dev-master"
```

to the required section of your `composer.json` file.

## Usage

Once the extension is installed, modify your application configuration to include:

```php
return [
	'modules' => [
	    ...
	        'rules' => [
				...
				[
					'pattern' => '<page>',
					'route' => 'emcms/page/index',
					'defaults' => ['page' => 'index'],
				],
			],
	    ...
	],
	...
	'components' => [
	    ...
		'emcms' => [
            'class' => 'almeyda\yii2-emcms\Module',
        ],
	    ...
	]
];
```

Please note that rule should be added at the end of the rules stack. Otherwise all simple requests will be processed by the emcms module.

## License

yii2-emcms is released under the Apache License 2.0. See the bundled [LICENSE.md](LICENSE.md) for details.


## URLs examples

* Index page: `yourhost`
* FAQ page: `yourhost/faq`
* About page: `yourhost/about`

