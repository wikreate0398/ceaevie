$(document).ready(function () {
	$('.datepicker').datepicker({
		dateFormat: 'dd-mm-yy',
		defaultDate: +7
	});

	$('a.confirm_link').click(function(e){  
        if (!confirm($(this).attr('data-confirm'))) {
            e.preventDefault();
        }
    });
});