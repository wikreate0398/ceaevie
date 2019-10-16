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
});

function scrollToBlock(id){
    $('html, body').animate({
        scrollTop: $(id).offset().top-75
    }, 1000);
}

function changeForms(from, to){
    $(from).hide();
    $(to).show();
}