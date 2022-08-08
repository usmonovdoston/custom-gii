$("body").on("submit", "." + active_from_class, function (e) {
    e.preventDefault();
    var self = $(this);
    var url = self.attr("action");
    let check = true;
    let required = self.find(".customRequired");
    $(required).each(function (index, value){
        if($(this).val()==0||$(this).val()==null){
            e.preventDefault();
            $(this).css("border-color","red").parents('.form-group').addClass('has-error');
            $(this).focus();
            check = false;
        }
    });
    if(check) {
        $(this).find("button[type=submit]").hide();
        // .attr("disabled", false); Bunda knopka 2 marta bosilsa 2 marta zapros ketyapti
        if (file_upload) {
            var formData = new FormData($(this)[0]);
            $.ajax({
                url: url,  //Server script to process data
                type: 'POST',
                // Form data
                data: formData,
                // beforeSend: beforeSendHandler, // its a function which you have to define
                success: function (response) {
                    if (response.status == 0) {
                        $.fn.eventSubmitSuccess(response);
                        $('#' + modal_id).modal("hide");
                        if(response.message){
                            success_message = response.message;
                        }
                        call_pnotify('success', success_message);
                        $.pjax.reload({container: "#" + grid_ajax});
                    } else {
                        let tekst = (response.message) ? response.message : fail_message;
                        $.each(response.errors, function (key, val) {
                            self.find(".field-" + model_type.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).addClass("has-error");
                            self.find(".field-" + model_type.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).find(".help-block").html(val);

                            if (array_model.length > 0) {
                                array_model.forEach(function (index, value) {
                                    self.find(".field-" + index.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).addClass("has-error");
                                    self.find(".field-" + index.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).find(".help-block").html(val);
                                });
                            }
                        });
                        self.find("button[type=submit]").show();
                        //.attr("disabled", false);
                        call_pnotify('fail', tekst);
                    }
                },
                error: function () {
                    console.log('ERROR at PHP side!!');
                    self.find("button[type=submit]").show();
                },
                //Options to tell jQuery not to process data or worry about content-type.
                cache: false,
                contentType: false,
                processData: false
            });
        } else {
            var data = $(this).serialize();
            $.ajax({
                url: url,
                data: data,
                type: "POST",
                success: function (response) {
                    if (response.status == 0) {
                        $.fn.eventSubmitSuccess(response);
                        $('#' + modal_id).modal("hide");
                        if(response.message){
                            success_message = response.message;
                        }
                        call_pnotify('success', success_message);
                        $.pjax.reload({container: "#" + grid_ajax});
                    } else {
                        $.fn.eventSubmitError(response);
                        let tekst = (response.message) ? response.message : fail_message;
                        let error = response.errors;
                        if(typeof error == 'object') {
                            $.each(error, function (key, val) {
                                self.find(".field-" + model_type.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).addClass("has-error");
                                self.find(".field-" + model_type.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).find(".help-block").html(val);
                                if (array_model.length > 0) {
                                    array_model.forEach(function (index, value) {
                                        self.find(".field-" + index.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).addClass("has-error");
                                        self.find(".field-" + index.toLowerCase().replace(/[_\W]+/g, "") + "-" + key).find(".help-block").html(val);
                                    });
                                }
                            });
                        }else{
                            call_pnotify('fail',error);
                        }
                        let list_error = response.list_errors;
                        if(typeof list_error == 'object') {
                            $.each(list_error, function (table, item) {
                                if(typeof item == 'object') {
                                    $.each(item, function (key, val) {
                                        if(typeof val == 'object') {
                                            $.each(val, function (num, value) {
                                                if(typeof val == 'object') {
                                                    $.each(value, function (n, text) {
                                                        self.find(".field-" + table + "-" + key+"-"+num).addClass("has-error");
                                                        if(self.find(".field-" + table + "-" + key+"-"+num).find(".help-block").length == 0){
                                                            self.find(".field-" + table + "-" + key+"-"+num).append('<div class="help-block"></div>')
                                                        }
                                                        self.find(".field-" + table + "-" + key+"-"+num).find(".help-block").html(text);
                                                        call_pnotify('fail',text);
                                                    });
                                                }else{
                                                    call_pnotify('fail',val);
                                                }
                                            });
                                        }else{
                                            call_pnotify('fail',val);
                                        }
                                    });
                                }else{
                                    call_pnotify('fail',item);
                                }
                            });
                        }else{
                            call_pnotify('fail',list_error);
                        }
                        self.find("button[type=submit]").show();
                        //.attr("disabled", false);
                        call_pnotify('fail', tekst);
                    }
                },
                error: function () {
                    console.log('ERROR at PHP side!!');
                    self.find("button[type=submit]").show();
                },
            });
        }
    }else{
        call_pnotify('fail', "Barcha maydonlar to'ldirilmagan");
    }
});
$("body").delegate(".customRequired","change",function(){
    if($(this).val()!=0){
        $(this).parents('.input-group').css("border-color","#d2d6de");
        $(this).css("border-color","#d2d6de").parents('.form-group').removeClass('has-error').addClass('has-success');
    }
});
$("body").delegate("." + active_from_class +" :input","change",function () {
   let parent = $(this).parent();
   parent.removeClass("has-error");
   parent.find(".help-block").html("");
});
//// Function Create Modal
$.fn.openModalCreateWindow = function () {
    $("body").delegate("." + create_button,"click",function (e) {
        e.preventDefault();
        loadModal();
        $('#' + modal_id).modal('show');
        let url = (pretty_url)?$(this).attr('href'):widget_create;
        $('#' + modal_id).find('.modal-body').load(url, function () {
            $.fn.eventCreateWindowOpen();
        });
    });
};
$.fn.openModalCreateWindow();
//When pjax reload success recalled neModel
/*jQuery(document).on("pjax:success", "#" + grid_ajax, function (event) {
        $.fn.openModalCreateWindow();
    }
);*/



//// Function Update Modal
$.fn.openModalUpdateWindow = function () {
    $("body").delegate("." + update_button,"click",function (e) {
        e.preventDefault();
        loadModal();
        id = $(this).attr('data-form-id');
        $('#' + modal_id).modal('show');
        let url = (pretty_url)?$(this).attr('href'):widget_update +'?id=' + id;
        $('#' + modal_id).find('.modal-body').load(url);
    });
};
$.fn.openModalUpdateWindow();
//When pjax reload success recalled neModel
/*jQuery(document).on("pjax:success", "#" + grid_ajax, function (event) {
        $.fn.openModalUpdateWindow();
    }
);*/
//// Function View Modal
$.fn.openModalViewWindow = function () {
    $("body").delegate("." + view_button,"click",function (e) {
        e.preventDefault();
        loadModal();
        id = $(this).attr('data-form-id');
        let type = $(this).attr('data-type');
        $('#' + modal_id).modal('show');
        let url = (pretty_url)?$(this).attr('href'):widget_view +'?id=' + id;
        if(type){
            url = (pretty_url)?$(this).attr('href'):widget_view +'?id=' + id +'&type=' + type;
        }
        $('#' + modal_id).find('.modal-body').load(url);
    });
};
$.fn.openModalViewWindow();
//When pjax reload success recalled neModel
/*jQuery(document).on("pjax:success", "#" + grid_ajax, function (event) {
        $.fn.openModalViewWindow();
    }
);*/

//// Function Delete Item
$.fn.deleteItem = function () {
    $("body").delegate("." + delete_button,"click",function (e) {
        e.preventDefault();
        if(confirm(confirm_message)){
            let t = $(this);
            id = t.attr("data-form-id");
            let url =  (pretty_url)?t.attr('href'):widget_delete + '?id=' + id;
            $.ajax({
                type: "POST",
                url: url,
                success: function (result) {
                    if(result.status !== undefined){
                        if(result.status==0){
                            call_pnotify('success',result.message);
                            t.remove();
                        }else{
                            call_pnotify('fail',result.message);
                        }
                    }
                    else{
                        if(result == 'success'){
                            call_pnotify('success', 'Success');
                            t.remove();
                        }else{
                            call_pnotify('fail', 'Fail');
                        }
                    }
                    $.pjax.reload({container: "#" + grid_ajax});
                }
            });
        }

    });
};
$.fn.deleteItem();
//When pjax reload success recalled neModel
/*jQuery(document).on("pjax:success", "#"  + grid_ajax, function (event) {
        $.fn.deleteItem();
    }
);*/



//// Function Create Modal
$.fn.openModalCopyWindow = function () {
    $("body").delegate("." + copy_button,"click",function (e) {
        e.preventDefault();
        loadModal();
        id = $(this).attr('data-form-id');

        $('#' + modal_id).modal('show');

        $('#' + modal_id).find('.modal-body').load(widget_copy +'?id=' + id);

    });
};

$.fn.openModalCopyWindow();
//When pjax reload success recalled neModel
/*jQuery(document).on("pjax:success", "#" + grid_ajax, function (event) {
        $.fn.openModalCopyWindow();
    }
);*/

//// Function Default Url
$.fn.openModalDefaultWindow = function () {
    $("body").delegate(".default_button","click",function (e) {
        e.preventDefault();
        loadModal();
        id = $(this).attr('data-form-id');
        let default_url = (pretty_url)?$(this).attr('href'):$(this).attr('default-url') +'?id=' + id;
        $('#' + modal_id).modal('show');
        $('#' + modal_id).find('.modal-body').load(default_url);
    });
};
$.fn.openModalDefaultWindow();
//When pjax reload success recalled neModel
/*jQuery(document).on("pjax:success", "#" + grid_ajax, function (event) {
        $.fn.openModalDefaultWindow();
    }
);*/
//// Function Default Url
$.fn.openModalPaginationWindow = function () {
    $('body').delegate('.'+model_type.toLowerCase()+'-view .pagination li a','click',function(e){
        e.preventDefault();
        loadModal();
        let url = $(this).attr('href');
        $('#' + modal_id).modal('show');
        $('#' + modal_id).find('.modal-body').load(url);
    });
};
$.fn.openModalPaginationWindow();
//When pjax reload success recalled neModel
/*jQuery(document).on("pjax:success", "#" + grid_ajax, function (event) {
        $.fn.openModalPaginationWindow();
    }
);*/
function call_pnotify(status,text) {
    switch (status) {
        case 'success':
            if(!text){
                text = 'Success!';
            }
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:text,type:'success'});
            break;

        case 'fail':
            if(!text){
                text = 'Fail!';
            }
            PNotify.defaults.styling = "bootstrap4";
            PNotify.defaults.delay = 2000;
            PNotify.alert({text:text,type:'error'});
            break;
    }
}
function loadModal(){
    $('#'+modal_id).find('.modal-body').html('<div id="loading-img" style="position: relative;">\n' +
        '                <div class="showbox">\n' +
        '                    <div class="loader">\n' +
        '                        <svg class="circular" viewBox="25 25 50 50">\n' +
        '                            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>\n' +
        '                        </svg>\n' +
        '                    </div>\n' +
        '                </div>\n' +
        '            </div>');
}

$.fn.eventSubmitSuccess = function (response) {};
$.fn.eventSubmitError = function (response) {};
$.fn.eventCreateWindowOpen = function (response) {};
$('body').delegate('.print-btn','click', function (e) {
    window.print();
});