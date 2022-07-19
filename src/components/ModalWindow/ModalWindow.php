<?php
namespace usmonovdoston\customGii\components\ModalWindow;
use yii\bootstrap\Widget;

class ModalWindow extends Widget
{
    public $model;
    public $crud_name;
    public $modal_id;
    public $modal_header;
    public $active_from_class;
    public $update_button;
    public $create_button;
    public $view_button;
    public $delete_button;
    public $copy_button;
    public $save_button;
    public $modal_size;
    public $grid_ajax;
    public $confirm_message;
    public $array_model = [];
    public $options = [];
    public $pretty_url = false;
    public $file_upload = false;
    public $module_name;
    public function init(){}

    public function run() {
        $success_message = \Yii::t('app','Success');
        $fail_message = \Yii::t('app','Fail');
        return $this->render('view', [
            'model' => $this->model,
            'crud_name' => ($this->crud_name)?$this->crud_name:$this->model,
            'modal_id' => $this->modal_id,
            'modal_header' => $this->modal_header,
            'active_from_class' => $this->active_from_class,
            'update_button' => $this->update_button,
            'create_button' => $this->create_button,
            'view_button' => $this->view_button,
            'delete_button' => $this->delete_button,
            'copy_button' => $this->copy_button,
            'save_button' => $this->save_button,
            'modal_size' => $this->modal_size,
            'grid_ajax' => $this->grid_ajax,
            'confirm_message' => $this->confirm_message,
            'fail_message' => $fail_message,
            'success_message' => $success_message,
            'array_model' => $this->array_model,
            'modal_options' => $this->options,
            'pretty_url' => $this->pretty_url,
            'file_upload' => $this->file_upload,
            'module_name' => $this->module_name,
        ]);

    }
}