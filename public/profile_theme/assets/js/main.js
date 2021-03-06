$(document).ready(function () {
	$('.datepicker').datepicker({
    dateFormat: 'dd.mm.yy', 
	});

	$('a.confirm_link').click(function(e){  
      if (!confirm($(this).attr('data-confirm'))) {
        e.preventDefault();
      }
  });

  $('.number').keypress(function(event) {
    if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
        event.preventDefault();
    }
  });

  $('.eq-table-cell').each(function(){  
    $(this).find('thead td').css('width', (100/$(this).find('thead td').length) + '%');
    $(this).find('tbody td').css('width', (100/$(this).find('thead td').length) + '%'); 
  });

  $('input#verification_files').fileuploader({
    extensions: ['image/*'],
    addMore: true, 
    limit: 5,
    maxSize:10,
    captions: {
        button: function(options) { return 'Выбрать ' + (options.limit == 1 ? 'file' : 'файлы');},
        removeConfirmation: 'Подтвердить удаление',
        feedback: 'Выберите файлы',
        errors: {
            filesLimit: 'Вы можете загрузить не более ${limit} файлов.',
            filesType: 'Вы можете загрузить файлы формата jpg,jpeg,png',
            fileSize: '${name} is too large! Please choose a file up to ${fileMaxSize}MB.', 
        }
    }
  }); 

  $('.rating-stars').each(function(){
      var currentRating = $(this).data('current-rating');

      $(this).barrating({
          theme: 'fontawesome-stars-o',
          showSelectedRating: false,
          initialRating: parseFloat(currentRating),
          allowEmpty:true, 
          deselectable:true,
          emptyValue: 0,
          onSelect:function(value, text, event){ 
          } 
      }); 
      var state = ($(this).attr('data-readonly') == 'true') ? true : false; 
      $(this).barrating('readonly', state);  
  }); 

  inputMask();
});

function profilePhoto(fileName){

    var file = fileName.files[0];

    var reader = new FileReader(); 

    reader.readAsDataURL(file);

    var fileSize = parseInt(file["size"]) / 1000; 
    var fileExtension = ["image/gif", "image/jpeg", "image/png", "image/jpg"];
    var fileType = file["type"];

    if (fileSize > 2048) {
        alert('Максимальный размер изображения 2МБ');
        return;
    } 

    if (jQuery.inArray(fileType, fileExtension) == -1) {
        alert('Файл не неверного формата');
        return;
    }

    reader.onload = function (e) {  
        $('.cropper__section, #overlay').fadeIn(150); 
 
        $('.cropper__image_content').html('<img src="" id="image__crop">');
        var image = $('img#image__crop');

        $(image).attr('src', reader.result); 
        $('input#avatar').val(reader.result);

        var image = document.getElementById('image__crop');
        var button = document.getElementById('crop__btn');
         
        var croppable = false;
        var cropper = new Cropper(image, { 
          aspectRatio: 1,
          viewMode: 1,
          ready: function () { 
            croppable = true;
          }
        });

        button.onclick = function () {
          $('.cropper__section, #overlay').fadeOut(150);
          var croppedCanvas;
          var roundedCanvas;
          var roundedImage;

          if (!croppable) {
            return;
          }
 
          cropper = cropper.getCroppedCanvas().toDataURL();

          $('input#avatar').val(cropper);
          $('.profile__img').css('background-image', 'url('+cropper+')'); 
          $('.save__cropped_image').show(); 
          $('form.profile__image_form').submit();
        };
    }; 
}

function inputMask(){  
  $("input.price-mask, input.home-price-mask").inputmask("decimal",{
      alias: 'numeric',
      radixPoint:".", 
      groupSeparator: "", 
      digits: 2,
      autoGroup: true,
      allowMinus: false,
      placeholder: '', 
  });

  $('#ExpiryDate').inputmask('99/99');
  $('#CreditCardNumber').inputmask('9999 9999 9999 9999');
  $('#SecurityCode').inputmask('999'); 
}

function setMoney(input) {
  var val = parseFloat($(input).val().replace(/\s+/g, ''));
  if (!val || val < minimum_withdrawal) {
    $(input).addClass('input-danger');
    $('.total-withdraw').hide();
  }else{
    $(input).addClass('input-success');
    $('.total-withdraw').show();
    $('.total-withdraw span').text(priceString(val-commision_withdrawal, 0));
  }
}

function selectWorkType(select) {
  if($(select).val() == 'percent'){
    $('.percent_field').show();
  }else{
    $('.percent_field').hide();
  }
}

function number_format(e,n,t,i){e=(e+"").replace(/[^0-9+\-Ee.]/g,"");var r=isFinite(+e)?+e:0,a=isFinite(+n)?Math.abs(n):0,o="undefined"==typeof i?",":i,d="undefined"==typeof t?".":t,u="",f=function(e,n){var t=Math.pow(10,n);return""+(Math.round(e*t)/t).toFixed(n)};return u=(a?f(r,a):""+Math.round(r)).split("."),u[0].length>3&&(u[0]=u[0].replace(/\B(?=(?:\d{3})+(?!\d))/g,o)),(u[1]||"").length<a&&(u[1]=u[1]||"",u[1]+=new Array(a-u[1].length+1).join("0")),u.join(d)}

function priceString(price, a){  
    var a = (a != undefined) ? a : 2;
    if (!price) {
        return '0';
    } 
    return number_format(price, a, '.', ' ');
} 