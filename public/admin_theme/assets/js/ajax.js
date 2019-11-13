$(document).ready(function(){
	$(document).on('submit', 'form.ajax__submit', function(e){
        e.preventDefault();
 
        var form   = $(this);
        var button = $(form).find('button[type="submit"]');
        var action = $(form).attr('action');
        $(button).attr('disabled', true);

        var button_width = $(button).width();

        var button_height = $(button).height(); 
        var button_txt    = $(button).text();
        var loader        = '<div class="loader-inner ball-pulse">' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '</div>';
        $(button).html(loader);
        $(button).width(button_width);
        $(button).height(button_height);

        setTimeout(function(){
            serializeForm(form, button, action, button_txt);
        }, 500);
    });
 
    $('div.nestable').each(function(i){ 
        if(!$(this).hasClass('nestable-disabled')){
            var newClass = 'nestable_' + i;
            $(this).addClass(newClass);
            var depth = $(this).attr('data-depth') ? $(this).attr('data-depth') : 1;

            $('.' + newClass).nestable({
                collapsedClass: 'dd-collapsed',
                maxDepth:parseInt(depth),
                axis: 'y',
                noDragClass: 'no-drag',     
                handleClass: 'dd-handle'
            }).nestable('expandAll').on('change', function() { 
                Ajax.nestable($(this));
            }); 
        }
    });

    $('#sort-items').each(function(i){
        var id='sort-'+i;
        $(this).attr('id', id);
        Ajax.sortItems("#"+id);
    });

    changeByKeyup();
});

function changeByKeyup(){
    var delay = (function(){
      var timer = 0;
      return function(callback, ms){
        clearTimeout (timer);
        timer = setTimeout(callback, ms);
      };
    })();
 
    $('body').find('.change_keyup').each(function(){ 
        var area = $(this);
        $(this).keyup(function() {
            delay(function(){  
                $(area).change()
            }, 200 );
        });
    }); 
}  

function serializeForm(form, button, action, button_txt){
	 
    $.ajax({
        url: action,
        type: 'POST',
        async: true,
        data: new FormData(form[0]),
        contentType: false,
        cache: false,
        processData: false,
        dataType: 'json',
        beforeSend: function() {},
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            if (XMLHttpRequest.status === 401) document.location.reload(true);
        },
        success: function(jsonResponse, textStatus, request) {   
            $(form).find('.error__input').removeClass('error__input');
            if (jsonResponse.msg === false) {
                var message = ''; 
                if (typeof jsonResponse.messages == 'object') { 
                    $.each(jsonResponse.messages, function(key, value){ 
                        $(form).find('[name="'+key+'"]').filter(function() {
                            return !this.value;
                        }).addClass("error__input") 
                        var br='';
                        $.each(value, function(k,v){
                            message += '<p>'+v+'</p>';
                        }); 
                    }); 
                }else{
                    message += '<p>' + jsonResponse.messages + '</p>'; 
                }
                Ajax.notify('danger', message); 
                //$(form).find('#error-respond').fadeIn().html(message);
                // setTimeout(function() {
                //     $(form).find('#form-respond').fadeOut(700);
                // }, 1000);

            } else {   
                if ($(form).attr('data-redirect')) {
                    window.location = $(form).attr('data-redirect'); 
                } else{
                    if (jsonResponse.redirect !== undefined) {   
                        window.location = jsonResponse.redirect; 
                    }

                    if (jsonResponse.reload == true) { 
                        window.location.reload(true);
                    } 

                    if (jsonResponse.message !== undefined) {
                        Ajax.notify('success', jsonResponse.message);

                        if (!$(form).hasClass('no-reset')) {
                            $(form)[0].reset(); 
                        } 
                    }  
                } 
            } 
        },
        complete: function() {
            $(button).attr('disabled', false);
            $(button).css({
                'padding-left': '0',
                'padding-right': '0'
            });
            $(button).text(button_txt);
        }
    });
}

var fixHelper = function(e, ui) {
    ui.children().each(function() {
        $(this).width($(this).width());
    });
    return ui;
};

var Ajax = {
    sortItems: function(idElement){
        $(idElement).sortable({
            helper: fixHelper,
            // containment:'table',
            forcePlaceholderSize: true,
            placeholder: 'group_move_placeholder',
            handle: ".handle",
            revert: 300,
            start:function(event, ui){
                $('.group_move_placeholder').height($(idElement).find('tr').height());
                var id_item = $(ui.item).attr('id');
                if ($('[data-parent="'+id_item+'"]').length>0 ) {
                    $('[data-parent="'+id_item+'"]').hide();
                }
            },

            update: function (event, ui) {
                var id_item = $(ui.item).attr('id');
                if ($('[data-parent="'+id_item+'"]').length>0) {
                    $('[data-parent="'+id_item+'"]').insertAfter($('#' + id_item));
                    $('[data-parent="'+id_item+'"]').show();
                }
            },
            stop: function() {
                var arr = $(idElement).sortable('toArray');
                var table = $(idElement).attr('data-table');

                jQuery.ajax({
                    type: 'POST',
                    url: '/'+adminArea+'/ajax/sortElement',
                    dataType: 'json',
                    data: {
                        arr: arr,
                        table: table,
                        _token: CSRF_TOKEN
                    },
                    beforeSend: function() {},
                    error: function(XMLHttpRequest, textStatus, errorThrown) {
                        if (XMLHttpRequest.status === 401) document.location.reload(true);
                    },
                    success: function(res) {
                        if (res.msg === 'error') {
                            Ajax.notify('error', res.cause);
                        } else {
                            Ajax.notify('success', res.message);
                        }
                    },
                    complete: function() {}
                });
            }
        });
    },

    toDelete: function(element, table, id, conf){

        if (conf == true) {
            if (confirm('Вы действительно хотите удалить?') == false) {
                return false;
            }
        }

        jQuery.ajax({  
            url: '/'+adminArea+'/ajax/deleteElement',
            type: 'POST', 
            data: {
                id: id,
                table: table,
                _token: CSRF_TOKEN
            }, 
            headers: {'X-CSRF-TOKEN': CSRF_TOKEN}, 
            dataType: 'json',  
            beforeSend: function() {},
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (XMLHttpRequest.status === 401) document.location.reload(true);
            },
            success: function(res) {

                if (res.msg === 'error') { 
                    Ajax.notify('error', res.cause); 
                } else { 
                    Ajax.notify('success', res.message);
                    $('.modal, .modal-backdrop').fadeOut(100);
                    if ( $(element).closest('.dd-item').length == true) {
                        $(element).closest('.dd-item').fadeOut(200);
                    } else if($(element).closest('.row.multi__container').length == true){
                        $(element).closest('.row').remove();
                    }else if($(element).closest('.thumbnail__item').length == true){
                        $(element).closest('.thumbnail__item').remove();
                    } else{
                        if ($(element).closest('tbody').find('tr').length <= 1) {
                            $(element).closest('table').fadeOut(200);
                            $(element).closest('table').prev('.cat__name').fadeOut(200); 
                        }else{
                            $(element).closest('tr').fadeOut(200, function(){
                                $(element).closest('tr').remove();
                            });
                        } 
                    } 
                }
            },
            complete: function() {}
        }); 
    },

    deleteImg: function(element, table, id, name){ 
        $.ajax({
            type: "POST",
            url: '/'+adminArea+'/ajax/deleteImg',
            data: {
                'id': id,
                'table': table,
                'name' : !name ? null : name,
                '_token': CSRF_TOKEN
            },
            dataType: 'json',
            success: function(res) {
                if (res.msg === 'error') {
                    Ajax.notify('error', res.cause); 
                } else { 
                    Ajax.notify('success', res.message);

                    var parent = $('.fileupload');  
                    $(element).closest(parent).find('.modal').fadeOut(100);
                    $('.modal-backdrop').fadeOut(100); 
                      
                    $(element).closest(parent).find('.modal').fadeOut(100);
                    if (name == 'swf') {
                        $(element).closest('.fileupload').find('#thumb-img').remove();
                        $('#swf-file').html('<img src="http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image" alt=""/>');
                    }else{
                        $(element).closest(parent).find('#thumb-img').attr('src', 'http://www.placehold.it/200x150/EFEFEF/AAAAAA&amp;text=no+image');
                    }

                    $('.target_image_' + id).remove();
                 
                    $(element).closest(parent).find('.del_btn').hide();
                }
            }
        }); 
    },

    nestable: function(item){

        var arr = $(item).nestable('serialize');
        var table = $(item).attr('data-table'); 
        var action = $(item).attr('data-action')
        var depth = $(item).attr('data-depth') ? $(item).attr('data-depth') : 1;

        jQuery.ajax({  
            url: action,
            type: 'POST', 
            data: {
                'arr': arr,
                'depth': depth,
                'table': table,
                '_token': CSRF_TOKEN
            }, 
            headers: {'X-CSRF-TOKEN': CSRF_TOKEN}, 
            dataType: 'json',  
            beforeSend: function() {},
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (XMLHttpRequest.status === 401) document.location.reload(true);
            },
            success: function(res) {
                if (res.msg === 'error') { 
                    Ajax.notify('error', res.cause); 
                } else { 
                    //Ajax.notify('success', res.message); 
                }
            },
            complete: function() { 
            }
        }); 
    },

    buttonView: function(click, table, id, row) {  
        row       = !row ? 'view' : row; 
        var state = $(this).prop('checked');   
        $.ajax({
            type: "POST",
            url: '/'+adminArea+'/ajax/viewElement',
            data: {
                'id': id,
                'state': state,
                'table': table,
                'row' : row
            },
            headers: {'X-CSRF-TOKEN': CSRF_TOKEN},
            dataType: 'json',
            beforeSend: function() {},
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                if (XMLHttpRequest.status === 401) document.location.reload(true);
            },
            success: function(res) {
                if (res.msg === 'error') { 
                    Ajax.notify('error', res.cause); 
                } else { 
                    Ajax.notify('success', res.message); 
                }
            }
        }); 
    },

    notify: function(status, message){
        $('.modal, .modal-backdrop').fadeOut(100);
        $('[data-notify="container"]').fadeOut(200);
        $.notify({
         // options 
         // title: 'Bootstrap notify',
         message: message, 
         target: '_blank'
      },{
         // settings
         element: 'body', 
         type: status, 
         placement: {
            from: "top",
            align: "right"
         },
         offset: 20,
         spacing: 10,
         z_index: 1031,
         delay: 2000,
         timer: 1000 ,
      });
    },

    loadRegionCities: function(select, city){
        var val      = $(select).val(); 
        var cacheStr = String((new Date()).getTime()).replace(/\D/gi, '');
        $( ".cities__area").load("/"+adminArea+"/location/loadRegionCities?rnd=" + cacheStr,
                                 {'id': $(select).val(), 'id_city': city, '_token': CSRF_TOKEN}, 
                                 function( response, status, xhr ) { 
            if ( response == "" ) {
                $('.cities__area').hide();
                $('.regions__area').removeClass('col-md-6').addClass('col-md-12');
            }else{
                $('.cities__area').show(); 
                $('.regions__area').removeClass('col-md-12').addClass('col-md-6');
            }
            initSelect2();
        }); 
    },

    onChangeSelect: function(select, table, row, id){
        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: "/"+adminArea+'/ajax/updateOnChange',
            data: {'status': status, 'id': id, val: $(select).val(), 'table': table, 'row': row, '_token': CSRF_TOKEN},
            success: function(jsonRespond){}
        });
    } 
}
 