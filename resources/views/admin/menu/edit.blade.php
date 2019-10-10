@extends('layouts.admin')

@section('content')
<div class="row">
	<div class="col-md-12"> 
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
	 
				<form action="/{{ $method }}/{{ $data['id'] }}/update" class="ajax__submit form-horizontal">  

					{{ csrf_field() }}

					<div class="form-body" style="padding-top: 20px;"> 
						<div class="tab-content">
							<div class="tab-pane active" id="tab_1"> 
								@include('admin.utils.input', ['label' => 'Название', 'lang' => true, 'name' => 'name', 'data' => $data])

								@include('admin.utils.input', ['label' => 'Ссылка', 'req' => true, 'name' => 'url', 'help' => 'Без http://www и.т.п просто английская фраза, без пробелов, отражающая пункт меню, например Наш подход - our-approach', 'data' => $data])

								@include('admin.utils.textarea', ['label' => 'Текст', 'lang' => true, 'name' => 'text', 'ckeditor' => true, 'data' => $data])
							</div>
						 
							<div class="tab-pane" id="tab_2">
								@include('admin.utils.input', ['label' => 'Заголовок', 'lang' => true, 'name' => 'seo_title', 'data' => $data])

								@include('admin.utils.textarea', ['label' => 'Описание', 'lang' => true, 'name' => 'seo_description', 'data' => $data])

								@include('admin.utils.input', ['label' => 'Ключевые слова', 'lang' => true, 'name' => 'seo_keywords', 'data' => $data])
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
</div>
 
@stop