<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$modelName = ltrim($generator->modelClass, '\\');
echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model <?= ltrim($generator->modelClass, '\\') ?> */

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => <?= $generator->generateString(Inflector::pluralize(Inflector::camel2words(StringHelper::basename($generator->viewPath)))) ?>, 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="<?= Inflector::camel2id(StringHelper::basename($generator->viewPath)) ?>-view">
    <?="<?php"?> if(!Yii::$app->request->isAjax){?>
    <div class="pull-right" style="margin-bottom: 15px;">
        <?="<?php //"?> if (Yii::$app->user->can('<?= Inflector::camel2id(StringHelper::basename($generator->viewPath)) ?>/update')): ?>
            <?="<?php // if (\$model->status < \$model::STATUS_SAVED): ?>\n"?>
                <?= "<?= " ?>Html::a(<?= $generator->generateString('Update') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
            <?="<?php // endif; ?>\n"?>
        <?="<?php // endif; ?>\n"?>
        <?="<?php //"?> if (Yii::$app->user->can('<?= Inflector::camel2id(StringHelper::basename($generator->viewPath)) ?>/delete')): ?>
            <?="<?php // if (\$model->status < \$model::STATUS_SAVED): ?>\n"?>
                <?= "<?= " ?>Html::a(<?= $generator->generateString('Delete') ?>, ['delete', <?= $urlParams ?>], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => <?= $generator->generateString('Are you sure you want to delete this item?') ?>,
                        'method' => 'post',
                    ],
                ]) ?>
            <?="<?php // endif; ?>\n"?>
        <?="<?php // endif; ?>\n"?>
        <?= "<?= " ?> Html::a(Yii::t('app', 'Back'), ["index"], ['class' => 'btn btn-info']) ?>
    </div>
    <?="<?php }?>\n"?>
    <?= "<?= " ?>DetailView::widget([
        'model' => $model,
        'attributes' => [
<?php
if (($tableSchema = $generator->getTableSchema()) === false) {
    foreach ($generator->getColumnNames() as $name) {
        if($name != 'status' && $name != 'created_by' && $name != 'updated_by' && $name != 'created_at' && $name != 'updated_at') {
            echo "            [\n
                                'attribute' => '" . $name . "',\n
                               ],\n";
        }else{
            if($name=='status'){
                echo "[
                    'attribute' => 'status',
                    'value' => function(\$model){
                        \$status = <?= ltrim($generator->modelClass, '\\') ?>::getStatusList(\$model->status);
                        return isset(\$status)?\$status:\$model->status;
                    }
                ],\n";
            }elseif($name=='created_by' || $name=='updated_by'){
                echo "            [
                'attribute' => '".$name."',
                'value' => function(\$model){
                    \$username = \app\models\Users::findOne(\$model->".$name.")['user_fio'];
                    return isset(\$username)?\$username:\$model->".$name.";
                }
            ],\n";
            }elseif($name=='created_at' || $name=='updated_at'){
                echo "            [
                'attribute' => '".$name."',
                'value' => function(\$model){
                    return (time()-\$model->".$name."<(60*60*24))?Yii::\$app->formatter->format(date(\$model->".$name."), 'relativeTime'):date('d.m.Y H:i',\$model->".$name.");
                }
            ],\n";
            }else {
                echo "            [\n
                                'attribute' => '" . $name . "',\n
                               ],\n";
            }
        }
    }
} else {
    foreach ($generator->getTableSchema()->columns as $column) {
        $format = $generator->generateColumnFormat($column);
        if($column->name != 'status' && $column->name != 'created_by' && $column->name!='updated_by' && $column->name != 'created_at' && $column->name != 'updated_at') {
            echo "            [
                'attribute' => '" . $column->name . "',
            ],\n";
        }else{
            if($column->name=='status'){
                echo "            [
                'attribute' => '".$column->name."',
                'value' => function(\$model){
                    \$status = \$model::getStatusList(\$model->status);
                    return isset(\$status)?\$status:\$model->status;
                }
            ],\n";
            }elseif($column->name=='created_by' || $column->name=='updated_by'){
                echo "            [
                'attribute' => '".$column->name."',
                'value' => function(\$model){
                    \$username = \app\models\Users::findOne(\$model->".$column->name.")['user_fio'];
                    return isset(\$username)?\$username:\$model->".$column->name.";
                }
            ],\n";
            }elseif($column->name=='created_at' || $column->name=='updated_at'){
                echo "            [
                'attribute' => '".$column->name."',
                'value' => function(\$model){
                    return (time()-\$model->".$column->name."<(60*60*24))?Yii::\$app->formatter->format(date(\$model->".$column->name."), 'relativeTime'):date('d.m.Y H:i',\$model->".$column->name.");
                }
            ],\n";
            }else {
                echo "            '" . $column->name . "',\n";
            }
        }
    }
}
?>
        ],
    ]) ?>

</div>
