@extends('layouts.admin')

@section('content')

	@if(request()->dev)
		<div class="note note-success" onclick="$('.taggle_win').slideToggle();" style="cursor:pointer; background-color:#F5F5F5;">
			<i class="fa fa-plus"></i> Add
		</div>

		<div class="row taggle_win">
			<div class="col-md-12">
				<div class="portlet light bg-inverse">
					<div class="portlet-body form">
						<!-- BEGIN FORM-->
						<form action="/{{ $method }}/createConstant" method="POST" class="ajax__submit form-horizontal" data-redirect="/{{ $method }}/?dev=1">
							{{ csrf_field() }}
							<div class="form-body">

								<div class="form-group">
									<label class="col-md-12 control-label">Define Name</label>
									<div class="col-md-12">
										<input type="text" class="form-control" name="name">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-12 control-label">Description</label>
									<div class="col-md-12">
										<input type="text" class="form-control" name="description">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-12 control-label">Editor</label>
									<div class="col-md-12">
										<input type="checkbox" name="editor">
									</div>
								</div>

								<div class="form-group">
									<label class="col-md-12 control-label">Category
									</label>
									<div class="col-md-12">
										<select class="form-control" name="id_category">
											<option value="0">Select...</option>
											@foreach($categories as $item)
												<option value="{{ $item['id'] }}">{{ $item["name"] }}</option>
											@endforeach
										</select>
									</div>
								</div>

								@foreach(Language::get() as $key => $language)
									<div class="form-group">
										<label class="col-md-12 control-label">Value {{ $language->short }}</label>
										<div class="col-md-12">
											<textarea name="value[{{ $language->short }}]" class="form-control"></textarea>
										</div>
									</div>
								@endforeach


							</div>
							<div class="form-actions">
								<div class="row">
									<div class="col-md-4">
										<button type="submit" class="btn green">Save</button>
									</div>
								</div>
							</div>
						</form>
						<!-- END FORM-->
					</div>
				</div>
			</div>
		</div>
	@endif

	<div class="row">
		<form action="/{{ $method }}/create" class="ajax__submit form-horizontal">

			{{ csrf_field() }}


			<div class="col-md-12">
				<div class="list-group">
					@foreach($data as $category => $constants) 
						<div class="list-group-item">
							<h2 class="list-group-item-heading">{{ $category }}</h2>
							<hr>
							<div class="list-group-item-text">
								@foreach($constants as $constant)
									<h4>{{ $constant->description }}</h4>
									<div class="row">
										@foreach(Language::get() as $key => $language)
											<div class="col-md-6">
												<span style="display: block;" class="label label-info">{{ $language->name }}</span>
												<textarea name="data[{{ $constant->id }}][{{ $language->short }}]"
														  class="form-control {{ $constant->editor ? 'ckeditor' : '' }}"
														  style="max-width: 100%; min-width: 100%; min-height: 55px;">{{ @$constant->constants_value->keyBy('lang')[$language->short]['value'] }}</textarea>
											</div>
										@endforeach
									</div>
								@endforeach
							</div>
						</div> 
					@endforeach
				</div>
			</div>
 
			<div class="col-md-12" style="margin-top: 20px;">
				<button type="submit" class="btn green">Save</button>
			</div> 
		</form>
	</div>
@stop

