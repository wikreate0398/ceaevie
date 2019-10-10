@extends('layouts.admin') 
 
@section('content')
	 
	<div class="row">
		<div class="col-md-12" style="margin-bottom: 20px;">
			<a href="#add_panel" class="btn btn-primary btn-sm open-area-btn">
				<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Добавить
			</a> 
		</div>

		<div class="col-md-12 area-panel" id="add_panel"> 
			<div class="portlet light bg-inverse"> 

				<div class="portlet-title">
		            <div class="caption">
		               <span class="caption-subject font-red-sunglo bold uppercase"> </span> 
		               <div class="tabbable-line">
		                  <ul class="nav nav-tabs" >
								<li class="active">
									<a href="#tab_1" data-toggle="tab">
									Основное </a>
								</li> 
								<li class="">
									<a href="#tab_2" data-toggle="tab">
									Seo </a>
								</li> 
							</ul> 
		               </div>
		            </div>
		            @include('admin.utils.language_switcher') 
		         </div>
	 
				<div class="portlet-body form">   
	 
					<form action="/{{ $method }}/create" class="ajax__submit form-horizontal">  

						{{ csrf_field() }}

						<div class="form-body" style="padding-top: 20px;"> 
							<div class="tab-content">
								<div class="tab-pane active" id="tab_1"> 
									@include('admin.utils.input', ['label' => 'Название', 'lang' => true, 'name' => 'name'])

									@include('admin.utils.input', ['label' => 'Ссылка', 'req' => true, 'name' => 'url', 'help' => 'Без http://www и.т.п просто английская фраза, без пробелов, отражающая пункт меню, например Наш подход - our-approach'])

									@include('admin.utils.textarea', ['label' => 'Текст', 'lang' => true, 'name' => 'text', 'ckeditor' => true])
								</div>
							 
								<div class="tab-pane" id="tab_2">
									@include('admin.utils.input', ['label' => 'Заголовок', 'lang' => true, 'name' => 'seo_title'])

									@include('admin.utils.textarea', ['label' => 'Описание', 'lang' => true, 'name' => 'seo_description'])

									@include('admin.utils.input', ['label' => 'Ключевые слова', 'lang' => true, 'name' => 'seo_keywords'])
								</div>
							</div> 
						</div>
						<div class="form-actions">
							<div class="btn-set pull-left"> 
								<button type="submit" class="btn green">Сохранить</button>
							</div> 
						</div>
					</form> 

				</div>
			</div>
		</div>

	   	<div class="col-md-12">  
	      	<table class="table table-bordered">
				<tbody>
				<tr>
					<td style="width:50px; text-align:center;"></td>
					<th style="width:5%; text-align: center">Показать <br> в шапке</th>
					<th style="width:5%; text-align: center">Показать <br> в подвале</th>
					<th class="nw">Заголовок</th> 
					<th style="width:5%; text-align: center"><i class="fa fa-cogs" aria-hidden="true"></i></th>
				</tr>
				</tbody>
				<tbody id="sort-items" data-table="{{ $table }}" data-action="{{ route('sortElement') }}">
				@foreach($menu as $item)
					<tr id="<?=$item['id']?>">
						<td style="width:50px; text-align:center;" class="handle"> </td> 
						<td style="width:5px; white-space: nowrap;">
							<input type="checkbox"
								   class="make-switch" data-size="mini" {{ !empty($item['view_top']) ? 'checked' : '' }}
								   data-on-text="<i class='fa fa-check'></i>"
								   data-off-text="<i class='fa fa-times'></i>"
								   onchange="Ajax.buttonView(this, '{{ $table }}', '{{ $item["id"] }}', 'view_top')">
						</td>
						<td style="width:5px; white-space: nowrap;">
							<input type="checkbox"
								   class="make-switch" data-size="mini" {{ !empty($item['view_bottom']) ? 'checked' : '' }}
								   data-on-text="<i class='fa fa-check'></i>"
								   data-off-text="<i class='fa fa-times'></i>"
								   onchange="Ajax.buttonView(this, '{{ $table }}', '{{ $item["id"] }}', 'view_bottom')">
						</td>
						<td class="nw">{{ $item->name_ru }}</td> 
						<td style="width: 5px; white-space: nowrap">
							<a style="margin-left: 5px;" href="/{{ $method }}/{{ $item['id'] }}/edit/" class="btn btn-primary btn-xs"><i class="fa fa-pencil"></i></a>

							@if(!$item->let_alone)
								<a class="btn btn-danger btn-xs" data-toggle="modal" href="#deleteModal_{{ $table }}_{{ $item['id'] }}"><i class="fa fa-trash-o "></i></a>
								<!-- Modal -->
									@include('admin.utils.delete', ['id' => $item['id'], 'table' => $table])
								<!-- Modal -->
							@endif
						</td>
					</tr>
				@endforeach
				</tbody>
			</table>
	   	</div>
	</div>
@stop

