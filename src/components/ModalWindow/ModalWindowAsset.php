<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace usmonovdoston\customGii\components\ModalWindow;

use yii\web\AssetBundle;

/**
 * This declares the asset files required by Gii.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class ModalWindowAsset extends AssetBundle
{
    public $sourcePath = '@vendor/usmonovdoston/custom-gii/src/components/ModalWindow/assets';
    public $css = [
        'css/modalWindow.css',
        'css/font-awesome.min.css',
    ];
    public $js = [
        'js/pnotify.js',
        'js/modal-window-widget.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
