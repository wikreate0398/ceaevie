$(document).ready(function () {
	$('.datepicker').datepicker({
    dateFormat: 'dd.mm.yy', 
	});

	$('a.confirm_link').click(function(e){  
        if (!confirm($(this).attr('data-confirm'))) {
            e.preventDefault();
        }
    });
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