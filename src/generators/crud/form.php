<?php
/* @var $this yii\web\View */
/* @var $form yii\widgets\ActiveForm */
/* @var $generator usmonovdoston\customGii\generators\crud\Generator */

use usmonovdoston\customGii\models\AdminGiiModules;

echo $form->field($generator, 'module')->dropDownList(AdminGiiModules::getModulesList());
echo $form->field($generator, 'crudname');
echo $form->field($generator, 'modelClass',['template'=>"<div class='row'><div class='col-md-3'>{label}</div><div class='col-md-4'><label><input type='radio' value='app' name='models'><small><code>\app\models\</code></small></label></div><div class='col-md-5'><label><input type='radio' value='modules' name='models' checked><small><code>\app\modules\models\</code></small></label></div></div>{input}"]);
echo $form->field($generator, 'searchModelClass');
echo $form->field($generator, 'controllerClass');
echo $form->field($generator, 'viewPath');
echo $form->field($generator, 'baseControllerClass');
echo $form->field($generator, 'indexWidgetType')->dropDownList([
    'grid' => 'GridView',
    'list' => 'ListView',
]);
echo $form->field($generator, 'enableI18N')->checkbox();
echo $form->field($generator, 'enablePjax')->checkbox();
echo $form->field($generator, 'messageCategory');
$url = explode('/', Yii::$app->request->pathInfo);
$url = (isset($url[1])) ? $url[1] : "yoq";
$customScript = <<< JS
$(function(){
    $("#generator-modelclass").blur(function(){
        var module = $("#generator-module").val();
        var massiv = $("#generator-modelclass").val().split("\\\");
        if($("#generator-modelclass").val()!=""&&massiv[massiv.length-1].match(/^[A-Z][a-z]+|[0-9]+/g)){
            if("$url"!="crud"){
                if(!massiv[2]){
                    $("#generator-modelclass").val('app\\\models\\\\'+massiv[massiv.length-1]);
                }       
                $("#generator-searchmodelclass").val($("#generator-modelclass").val()+'Search');
                massiv = $("#generator-modelclass").val().split("\\\");
                $("#generator-controllerclass").val('app\\\controllers\\\\'+massiv[2]+'Controller');
                $("#generator-viewpath").val('@app/'+'views/'+massiv[2].toLowerCase());
            }else{
                var massiv = $(this).val().split("\\\");
                if($("input[name='models']:checked").val()=='app'){
                    $("#generator-modelclass").val('app\\\models\\\\'+massiv[massiv.length-1]);
                }else{
                    $("#generator-modelclass").val('app\\\modules\\\\'+module+'\\\models\\\\'+massiv[massiv.length-1]);
                }
                if($("#generator-crudname").val()!=""){
                    var m = $("#generator-crudname").val().charAt(0).toUpperCase() + $("#generator-crudname").val().slice(1);     
                    $("#generator-searchmodelclass").val('app\\\modules\\\\'+module+'\\\models\\\\'+m+'Search');
                    $("#generator-controllerclass").val('app\\\modules\\\\'+module+'\\\controllers\\\\'+m+'Controller');
                    $("#generator-viewpath").val('@app/modules/'+module+'/'+'views/'+m.match(/[A-Z][a-z]+|[0-9]+/g).join("-").toLowerCase());
                }else{
                    // $("#generator-basecontrollerclass").val('app\\\modules\\\\'+module+'\\\controllers\\\BaseController');      
                    $("#generator-searchmodelclass").val($("#generator-modelclass").val()+'Search');
                    $("#generator-controllerclass").val('app\\\modules\\\\'+module+'\\\controllers\\\\'+massiv[massiv.length-1]+'Controller');
                    $("#generator-viewpath").val('@app/modules/'+module+'/'+'views/'+massiv[massiv.length-1].match(/[A-Z][a-z]+|[0-9]+/g).join("-").toLowerCase());
                }
            }
        }
    });
    $("input[name='models']").click(function(){
        var module = $("#generator-module").val();
        if($("#generator-modelclass").val()!=""){
            if("$url"!="crud"){
                var massiv = $("#generator-modelclass").val().split("\\\");
                if(!massiv[2]){
                    $("#generator-modelclass").val('app\\\models\\\\'+massiv[massiv.length-1]);
                }       
                $("#generator-searchmodelclass").val($("#generator-modelclass").val()+'Search');
                massiv = $("#generator-modelclass").val().split("\\\");
                $("#generator-controllerclass").val('app\\\controllers\\\\'+massiv[2]+'Controller');
                $("#generator-viewpath").val('@app/'+'views/'+massiv[2].toLowerCase());
            }else{
                var massiv = $("#generator-modelclass").val().split("\\\");
                if($("input[name='models']:checked").val()=='app'){
                    $("#generator-modelclass").val('app\\\models\\\\'+massiv[massiv.length-1]);
                }else{
                    $("#generator-modelclass").val('app\\\modules\\\\'+module+'\\\models\\\\'+massiv[massiv.length-1]);
                }
                if($("#generator-crudname").val()!=""){
                    var m = $("#generator-crudname").val().charAt(0).toUpperCase() + $("#generator-crudname").val().slice(1);   
                    $("#generator-searchmodelclass").val('app\\\modules\\\\'+module+'\\\models\\\\'+m+'Search');
                    $("#generator-controllerclass").val('app\\\modules\\\\'+module+'\\\controllers\\\\'+m+'Controller');
                    $("#generator-viewpath").val('@app/modules/'+module+'/'+'views/'+m.match(/[A-Z][a-z]+|[0-9]+/g).join("-").toLowerCase());
                }else{
                    // $("#generator-basecontrollerclass").val('app\\\modules\\\\'+module+'\\\controllers\\\BaseController');      
                    $("#generator-searchmodelclass").val($("#generator-modelclass").val()+'Search');
                    $("#generator-controllerclass").val('app\\\modules\\\\'+module+'\\\controllers\\\\'+massiv[massiv.length-1]+'Controller');
                    $("#generator-viewpath").val('@app/modules/'+module+'/'+'views/'+massiv[massiv.length-1].match(/[A-Z][a-z]+|[0-9]+/g).join("-").toLowerCase());
                }
            }
        }
    });
    $("input").focus(function(){
        $(this).select();
    });
    $("input").dblclick(function(){
        $(this).val('');
    });
    $("#generator-crudname").on('blur keyup',function(){
        var module = $("#generator-module").val();
        if($(this).val()!=""){
            var m = $(this).val().charAt(0).toUpperCase() + $(this).val().slice(1);     
            $("#generator-searchmodelclass").val('app\\\modules\\\\'+module+'\\\models\\\\'+m+'Search');
            $("#generator-controllerclass").val('app\\\modules\\\\'+module+'\\\controllers\\\\'+m+'Controller');
            $("#generator-viewpath").val('@app/modules/'+module+'/'+'views/'+m.match(/[A-Z][a-z]+|[0-9]+/g).join("-").toLowerCase());
        }
    });
});
JS;
$this->registerJs($customScript, \yii\web\View::POS_READY);
