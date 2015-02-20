<?php

namespace almeyda\emcms;

/**
 * The Emcms Module provides content rendering functionality
 *
 */
class Module extends \yii\base\Module
{
	/**
     * @var mixed string the directory storing the theme files in the application. 
     */
    public $appThemePath = '';
	
	/**
     * @var string a string to be prefixed to the user-specified view name to form a path to view folder.
     */
    public $viewsFolder = 'pages';
    
	/**
     * @var string the relative path to layout
     */
    public $layoutPath = 'layouts/main.php';
	
	/**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
		
    }
}
