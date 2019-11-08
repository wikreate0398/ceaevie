$(document).ready(function () {
	$('.datepicker').datepicker({
    dateFormat: 'dd.mm.yy', 
	});

	$('a.confirm_link').click(function(e){  
      if (!confirm($(this).attr('data-confirm'))) {
        e.preventDefault();
      }
  });

  $('input#verification_files').fileuploader({
    extensions: ['image/*'],
    addMore: true, 
    limit: 5,
    maxSize:2,
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
      groupSeparator: " ", 
      digits: 2,
      autoGroup: true,
      allowMinus: false  
  });

  $('#ExpiryDate').inputmask('99/99');
  $('#CreditCardNumber').inputmask('9999 9999 9999 9999');
  $('#SecurityCode').inputmask('999'); 
}

function setMoney(input) {
  var val = $(input).val();
  if (!val || val <= 30) {
    $(input).addClass('input-danger');
    $('.total-withdraw').hide();
  }else{
    $(input).addClass('input-success');
    $('.total-withdraw').show();
    $('.total-withdraw span').text(val-commision_withdrawal);
  }
}