@extends('layouts.public')

@section('content')
    <section class="page-container mb-50">
        <div class="container"> 
            <div class="row">
                <div class="col-md-12">
                    <h3 class="section-header mb-50 mt-50">
                        {{ $page_data["name_$lang"] }}
                    </h3>
                </div>
                <div class="page-text">
                    {!! $page_data["text_$lang"] !!}
                </div>
            </div>
        </div>
    </section>
@stop

