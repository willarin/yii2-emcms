<?php

if ($model->pageType == 'page') {
    $this->title = $model->title;

    $this->registerMetaTag(['name' => 'description', 'content' => $model->description]);
}
echo $model->renderContent($model->content);

echo $model->footerHtml;
