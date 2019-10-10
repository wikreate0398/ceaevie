@if(empty($lang))
	<div class="form-group">
		<label class="control-label col-md-12">{!! $label !!} {!! @$req ? '<span class="req">*</span>' : '' !!}</label>
		<div class="col-md-12"> 
		<input type="{{ empty($type) ? 'text' : $type }}" autocomplete="off" class="form-control {{ @$attributes['class'] }}" {{ @$req ? 'required' : '' }} value="{{ !empty($data[@$name]) ? $data[@$name] : '' }}" name="{{ $name }}">
		@if(!empty($help))
			<span class="help-block">{{ $help }}</span>
		@endif
		</div>
	</div> 
@else
	@foreach(Language::get() as $key => $language)
		<div class="form-group lang-area field_{{ $language['short'] }}">
			<label class="control-label col-md-12">{{ $label }} {!! @$req ? '<span class="req">*</span>' : '' !!}</label>
			<div class="col-md-12"> 
			<input type="text" 
			       class="form-control" 

			       value="{{ !empty($data[@$name.'_'.$language['short']]) ? $data[@$name.'_'.$language['short']] : '' }}" 
			       name="{{ $name }}[{{$language['short']}}]">

				@if(!empty($help))
					<span class="help-block">{{ $help }}</span>
				@endif
			</div>
		</div>
	@endforeach
@endif