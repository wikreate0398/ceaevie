var Ajax = function() {

    var self = this;

    this.serializeForm = function (form, button, action, button_txt) {
        $.ajax({
            url: action,
            type: 'POST',
            async: true,
            data: new FormData(form[0]),
            contentType: false,
            cache: false,
            processData: false,
            dataType: 'json',
            beforeSend: function() {
                if($(form).closest('.loader-v2-inner').length > 0){
                    $(form).closest('.loader-v2-inner').addClass('load-page');
                } 

                if ($(form).hasClass('profile__image_form')) {
                    $(form).addClass('loading__save_image');
                }  
            },
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

                    Notify.setStatus('danger').setMessage(message).show();

                } else {
 
                    if($(form).closest('#forgot-inner').length > 0){
                        toggleBlocks('#forgot-inner', '#auth-inner');
                    }

                    if ($(form).attr('data-redirect')) {
                        window.location = $(form).attr('data-redirect');
                    } else{
                        if (jsonResponse.redirect !== undefined) {
                            window.location = jsonResponse.redirect;
                        }

                        if (jsonResponse.open_window !== undefined) {
                            window.open(jsonResponse.open_window);
                        }

                        if (jsonResponse.reload == true) {
                            if(jsonResponse.messages !== undefined){
                                Notify.setStatus('success').setMessage(jsonResponse.messages).show();
                                setTimeout(function () {
                                    window.location.reload(true);
                                }, 1500);
                            }else{
                                window.location.reload(true);
                            }
                        }else{
                            if (jsonResponse.messages !== undefined) {
                                Notify.setStatus('success').setMessage(jsonResponse.messages).show();
                                $(form)[0].reset();
                            }
                        }

                        $('.modal').modal('hide');
                    }
                }
            },
            complete: function() {
                $(button).attr('disabled', false)
                         .css({
                            'padding-left': '0',
                            'padding-right': '0'
                         })
                         .text(button_txt);

                if($(form).closest('.loader-v2-inner').length > 0){
                    $(form).closest('.loader-v2-inner').removeClass('load-page');
                }

                $(form).removeClass('loading__save_image');
                $(form).removeClass('preloader__form');
                $(form).remove('.preloader__form_content');

                if (typeof inputMask !== 'undefined' && $.isFunction(inputMask)) {
                    inputMask();
                } 

                if (!$(button).length && $('.page-preloader').length) {
                    $('.page-preloader').css('display', 'none');
                }

                if($(form).hasClass('invitaion_form')){
                    $(form).remove();
                }
            }
        });
    };
};

var Ajax = new Ajax();

$(document).ready(function(){

    $('form.ajax__submit').submit(function(e){
        e.preventDefault();

        var form   = $(this);
        var button = $(form).find('button[type="submit"]');
        var action = $(form).attr('action');
        $(button).attr('disabled', true);

        var button_width = $(button).width();

        var button_height = $(button).height();
        var button_txt    = $(button).text();

        $(button).html('<div class="loader-inner ball-pulse">' +
            '<div></div>' +
            '<div></div>' +
            '<div></div>' +
            '</div>');

        $(button).width(button_width)
            .height(button_height);


        if (!$(button).length && $('.page-preloader').length) {
            $('.page-preloader').css('display', 'flex');
        }

        setTimeout(function(){
            Ajax.serializeForm(form, button, action, button_txt);
        }, 500);
    });
});
