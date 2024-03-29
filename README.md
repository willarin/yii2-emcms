Emcms Content Management Module
============

Emcms is a simple client-side Content Management Module for Yii2.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Place **composer.phar** file in the same directory with **composer.json** file and run

```
$ php composer.phar require almeyda/yii2-emcms "dev-master"
```

or add

```
{
    ...
    "require": {
        ...
        "almeyda/yii2-emcms": "dev-master"
        ...
    }
    ...
}
```

to the *"require"* section of your `composer.json` file and run

```
$ php composer.phar update
```
## Update database schema

The last thing you need to do is updating your database schema by applying the
migrations. Make sure that you have properly configured `db` application component
and run the following command:

```bash
$ php yii migrate/up --migrationPath=@vendor/almeyda/yii2-emcms/migrations
```

## Configuration

Once the extension is installed, modify your application configuration to include:

```php
return [
    ...
    'modules' => [
        ...
        'emcms' => [
           'class' => 'almeyda\emcms\Module',
        ],
        ...
    ],
    ...	        
    'components' => [
        ...
        'urlManager' => [
            'class' => 'codemix\localeurls\UrlManager',
            'rules' => [
                ...
                ...
                ...
                'emcms' =>  [
                        'pattern' => '<view>',
                        'route' => 'emcms/page/page',
                        'defaults' => ['view' => 'index'],
                ],
                ...
            ]
        ...
        ],
    ],
    ...             
]
];
```

Please note that rule 'emcms' should be added at the end of the rules stack. Otherwise all simple requests will be processed by the emcms module.

## Overriding views

When you start using yii2-emcms you will probably find that you need to override the default views provided by the module.
Although view names are not configurable, Yii2 provides a way to override views using themes. To get started you should
configure your view application component as follows:

```php
...
'components' => [
    'view' => [
        ...
        'theme' => [
            'pathMap' => [
                '@almeyda/emcms/views/layouts' => '@app/views/emcms/layouts',
                '@almeyda/emcms/views' => '@app/views/emcms/pages',
                ...
        ],
        ...
    ],
],
...
```

In the above `pathMap` means that every view in @almeyda/emcms/views will be first searched under `@app/views/emcms/pages` and
if a view exists in the theme directory it will be used instead of the original view.

## Usage

Create your index.php page in your theme folder and add your pages to this folder (like post1.php, post2.php, ...)

##Example structure of the folders:

```
views/                        (folder) main folder in the root of your site
    └─emcms/                  (folder) - folder containing all cms related views/layouts
        ├─layout/             (folder) - folder containing layout 
            └main.php         (file) - layout name. It matches 'pathMap' in the configuration        
        └─pages/              (folder) - folder containing all the views. It matches 'pathMap' in the configuration 
            ├─ index.php      (file) - file with the list of posts
            ├─ post1.php      (file) - file with the content of 1st post 
            └─ post2.php      (file) - file with the content of 2nd post
```               

### File examples

**[main.php]** 
layout used

```php
<?php
use yii\helpers\Html;
use yii\bootstrap\BootstrapAsset;
BootstrapAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>
<?= $content ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

```
**[index.php]** file with the business logic, that shows the list of posts in a blog 
```php

<?php $this->title = 'Title of a blog page' ?>

<?php $this->registerMetaTag(['name' => 'description', 'content' => 'some useful content for SEO']); ?>

<?php

# next step we form the list of files in the current directory

$posts = [];
chdir(__DIR__);
array_multisort(array_map('filemtime', ($files = glob("*.*"))), SORT_DESC, $files); 


# next step we prepare an array to use it in special function **

foreach ($files as $fileName) {
    if (!in_array($fileName, ['.', '..', 'index.php', 'contact.php'])) {
        $fileContent = file_get_contents(__DIR__ . '/' . $fileName, true);

        foreach (['title', 'description'] as $id) {
            preg_match('#<(.*?)id=\"' . $id . '\"[^>]*>(.*?)</(.&?)>#i', $fileContent, $matches);
            $post['url'] = '/blog/' . substr($fileName, 0, -4) . '.html';
            $post[$id] = strip_tags(@$matches[2]);
        }
        if ($post['title'] || $post['description']) {
            $posts[] = $post;
        }
    }
}

# next step we form the list of files in the current directory

$provider = new \yii\data\ArrayDataProvider([
    'allModels' => $posts,
    'pagination' => [
        'pageSize' => 10,
    ],
    'sort' => [
        'attributes' => ['title', 'description'],
    ],
]);

# next step we show the list of posts in the same html-blocks

foreach ($provider->getModels() as $post) : ?>
    <div class="row">
        <p><a HREF="<?= $post['url'] ?>" class="post-heading"><?= $post['title'] ?></a></p>
            <p><?= $post['description'] ?></p>
            <a HREF="<?= $post['url'] ?>" target="_self">Read More</a>
    </div>
<?php endforeach; ?>

# next step we show the pagination if nessesary

<div class="text-center">
    <?= \yii\widgets\LinkPager::widget(['pagination' => $provider->getPagination(),]);?>
</div>

```
**[post1.php]** Example file with some content for the 1st post 
```php

<?php $this->title = 'Title of the 1st blog post' ?>
<?php $this->registerMetaTag(['name' =>'description','content' =>'some useful content for SEO optimization' ]); ?>
<div class="row">
    <a HREF="yourhost/blog/" target="_self" class="btn btn-default">Back to blog</a>
</div>
<div class="row">
    <p>html-content of 1st blog page</p>
</div>

```
**[post2.php]** Example file with some content for the 2nd post 
```php

<?php $this->title = 'Title of the 2nd blog post' ?>
<?php $this->registerMetaTag(['name' =>'description','content' =>'some useful content for SEO optimization' ]); ?>
<div class="row">
    <a HREF="yourhost/blog/" target="_self" class="btn btn-default">Back to blog</a>
</div>
<div class="row">
    <p>html-content of 2nd blog page</p>
</div>
```

### URLs examples

* Index page: `yourhost`
* Blog page: `yourhost/blog`
* Blog page for 1st post: `yourhost/blog/post1.html`
* Blog page for 2st post: `yourhost/blog/post2.html`

### An example of creating a solitary new page

If you want to create page with address `yourhost/faq`  you could follow the next guide:
1. Change component section in the config file:

```
'components' => [
    ...
    'view' => [
        ...
        'theme' => [
            'pathMap' => [
                '@app/views/layouts' => '@app/views/themes/{your-theme}/common/layouts',
                '@almeyda/emcms/views/page/pages' => '@app/views/themes/{your-theme}/common/pages',
                ...
        ],
        ...
    ],
    ...
]
```
2. create the new file `/views/themes/{your-theme}/common/pages/faq.php`;
3. open the address `yourhost/faq`;

## Authentication support
[Read authentication support documentation](docs/authentication-support.md)

## License

Please, take a look at the bundled [LICENSE.md](LICENSE.md) for details.



