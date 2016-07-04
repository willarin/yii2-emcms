<?php

namespace almeyda\emcms\helpers;

use Yii;

class UrlCacheHelper
{

    /**
     * Format file path by adding datetime and language code segment if necessary
     * @param string $filePath path from to the assets folder (without first slash)
     * @return string
     */
    public static function getCachedUrl($filePath)
    {

        //define file path with language code
        $filePathArr = explode('/', $filePath);
        $filePathArr[count($filePathArr)] = $filePathArr[count($filePathArr) - 1];
        $filePathArr[count($filePathArr) - 2] = Yii::$app->language;
        $filePathWithLang = implode('/', $filePathArr);

        //define absolute and relative file path
        $fileAbsPath = Yii::getAlias('@webroot') . $filePathWithLang;
        if (file_exists($fileAbsPath)) {
            $filePath = $filePathWithLang;
        } else {
            $fileAbsPath = Yii::getAlias('@webroot') . $filePath;
        }

        //add datetime parameter
        if (file_exists($fileAbsPath)) {
            $filePath .= ((strpos($filePath, '?')) ? '&' : '?') . 'v=' . filemtime($fileAbsPath);
        }

        return $filePath;
    }

}
