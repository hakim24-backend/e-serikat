<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css',
        'src/custom.css',
        'css/demo.css',
        'css/fonts.css',
        'css/safari.css',
        'css/style.css',
        'css/variable.css',

    ];
    public $js = [
      'js/custom.js',
      'js/demo.js',
      'js/e-magz.js',
      'js/jquery.js',
      'js/jquery.migrate.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
