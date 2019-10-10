<div class="actions" id="change-content" style="padding-top:19px; float:left">
    @foreach(Language::get() as $key => $language)
		<a class="btn btn-circle btn-icon-only btn-default {{ $key==0 ? 'active' : '' }} change-lang" 
		   data-lang="{{ $language->short }}" 
		   href="javascript:;">
	   		<span>{{ $language->short }}</span>
	   	</a>
    @endforeach
</div>