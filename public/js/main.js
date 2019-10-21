$(document).ready(function(){
    $(window).scroll(function(e){
        var body = e.target.body, scrollT = $(this).scrollTop(); 
        if (scrollT > 200) {
            $('.navbar').addClass('fixed-header');
            $('.fixed-header').css({
                'top': "0",
                'opacity': '1'
            }); 
        }else{ 
            $('.navbar').removeClass('fixed-header');
        } 
    }); 

    $('.toggle-link').click(function(e){
        e.preventDefault(); 
        scrollToBlock($(this).attr('href')); 
    });  

    inputMask();

    changeByKeyup();

    $('.number').keypress(function(event) {
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });  
});

function scrollToBlock(id){
    $('html, body').animate({
        scrollTop: $(id).offset().top-75
    }, 1000);
}

function toggleBlocks(from, to){
    $(from).hide();
    $(to).show();
}
 
function inputMask(){
    $("input.code-mask").inputmask("999-9");
    $("input.price-mask, input.home-price-mask").inputmask("decimal",{
        alias: 'numeric',
        radixPoint:".", 
        groupSeparator: " ", 
        digits: 2,
        autoGroup: true,
        allowMinus: false  
    });

    $('#ExpiryDate').inputmask('99/99');
    $('#CreditCardNumber').inputmask('9999 9999 9999 9999');
    $('#SecurityCode').inputmask('999'); 
}

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
 