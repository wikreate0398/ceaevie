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

                    Notify.setStatus('danger').setMessage(message).show();

                } else {
                    if ($(form).attr('data-redirect')) {
                        window.location = $(form).attr('data-redirect');
                    } else{
                        if (jsonResponse.redirect !== undefined) {
                            setTimeout(function () {
                                window.location = jsonResponse.redirect;
                            }, 1500); 
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

        setTimeout(function(){
            Ajax.serializeForm(form, button, action, button_txt);
        }, 500);
    });
});
