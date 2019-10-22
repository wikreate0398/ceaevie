<form action="{{ $link }}" name="webpay_form" method="POST">
	@foreach($paymentData as $name => $value)
		<input type="hidden" name="{{ $name }}" value="{{ $value }}">
	@endforeach
</form>

<script type="text/javascript">
	document.webpay_form.submit(); 
</script>