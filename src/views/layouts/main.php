<?php

use yii\widgets\Menu;
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */

$asset = \usmonovdoston\customGii\GiiAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="none">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
    <div class="page-container">
        <?php $this->beginBody() ?>
        <nav class="navbar navbar-expand-md navbar-dark bg-dark">
            <div class="container">
                <?php echo Html::a(Html::img($asset->baseUrl . '/logo.png'), ['default/index'], [
                    'class' => ['navbar-brand']
                ]); ?>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#gii-nav"
                        aria-controls="gii-nav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="gii-nav">
                    <?php
                    echo Menu::widget([
                        'options' => ['class' => ['navbar-nav', 'ml-auto']],
                        'activateItems' => true,
                        'itemOptions' => [
                            'class' => ['nav-item']
                        ],
                        'linkTemplate' => '<a class="nav-link" href="{url}">{label}</a>',
                        'items' => [
                            ['label' => 'Home', 'url' => ['default/index']],
                            ['label' => 'Help', 'url' => 'http://www.yiiframework.com/doc-2.0/ext-gii-index.html'],
                            ['label' => 'Application', 'url' => Yii::$app->homeUrl],
                        ]
                    ]);
                    ?>
                </div>
            </div>
        </nav>
        <div class="container content-container">
            <?= $content ?>
        </div>
        <div class="footer-fix"></div>
    </div>
    <footer class="footer border-top">
        <div class="container">
            <div class="row">
                <div class="col-6">
                    <p>A Product of <a href="http://www.yiisoft.com/">Yii Software LLC</a></p>
                </div>
                <div class="col-6">
                    <p class="text-right"><?= Yii::powered() ?></p>
                </div>
            </div>
        </div>
    </footer>
    <?php
$url = explode('/', Yii::$app->request->pathInfo);
$url = (isset($url[1])) ? $url[1] : "yoq";
$customScript = <<< JS
$(function(){
    // $("#generator-searchmodelclass").blur(function(){
    //     var module = $("#generator-module").val();
    //     var massiv = $("#generator-searchmodelclass").val().split("\\\");
    //     if(!massiv[3]){
    //         $("#generator-searchmodelclass").val('app\\\modules\\\models\\\\'+$("#generator-searchmodelclass").val()+'Search');
    //     }       
    // });
    // $("#generator-controllerclass").blur(function(){
    //     var module = $("#generator-module").val();
    //     var massiv = $("#generator-controllerclass").val().split("\\\");
    //     if(!massiv[3]){
    //         $("#generator-controllerclass").val('app\\\modules\\\\'+module+'\\\controllers\\\\'+$("#generator-controllerclass").val()+'Controller');
    //     }       
    // });
    // $("#generator-viewpath").blur(function(){
    //     var module = $("#generator-module").val();
    //     var massiv = $("#generator-viewpath").val().split("\\\");
    //     if(!massiv[3]){
    //         $("#generator-viewpath").val('@app/modules/\'+module+'/'+'views/'+$("#generator-viewpath").val());
    //     }       
    // })
    // $("#generator-tablename").blur(function(){
    //     var module = $("#generator-module").val();
    //     var mass = $(this).val().split("_");
    //     if(mass[1]){
    //         $("#generator-modelclass").val(mass[1].substr(0,1).toUpperCase()+mass[1].substr(1));
    //     }else{
    //         $("#generator-modelclass").val(mass[0].substr(0,1).toUpperCase()+mass[0].substr(1));
    //     }      
    // })
})
JS;
$this->registerJs($customScript, \yii\web\View::POS_READY);
?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
