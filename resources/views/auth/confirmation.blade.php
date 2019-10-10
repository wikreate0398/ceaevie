@extends('layouts.public')

@section('content')
    <section class="text_container">
        <div class="container">
            <div class="row">
                @if(!empty($user->id))
                    @if(empty($user->active))
                        {{ \Constant::get('PROFILE_ACTIVATE') }}
                    @else
                        {{ \Constant::get('ALREADY_ACTIVATED') }}
                    @endif
                @else
                    {{ \Constant::get('ACTIVATION_ERROR') }}
                @endif
            </div>
        </div>
    </section>
@stop

