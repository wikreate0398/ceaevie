@extends('layouts.personal_profile')

@section('content')
    <script>
        var pageUrl = '{{ route('enrollment', ['lang' => $lang]) }}';
    </script>
    <div class="page-header">
        <h3 class="page-title">
    <span
        class="page-title-icon bg-gradient-danger text-white mr-2">
      <i class="mdi mdi-chart-line"></i>
    </span> История зачислений </h3>
    </div>

    @include('profile.utils.cards')
    
    @if($tips->count())
        <form action="{{ route('enrollment', ['lang' => $lang]) }}">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="title">Отобразить платежи за период</h4>
                </div>
                
                <div class="col-md-4 grid-margin">
                    <div class="input-date">
                        <input type="text" class="datepicker"
                               placeholder="dd.mm.yy"
                               name="from"
                               value="{{ request()->from }}"
                               autocomplete="off">
                        <img src="{{ asset('profile_theme') }}/assets/images/calendar.png"
                             alt="calendar">
                    </div>
                </div>
                <div class="col-md-4 grid-margin">
                    <div class="input-date">
                        <input type="text" class="datepicker"
                               placeholder="dd.mm.yy"
                               name="to"
                               value="{{ request()->to }}"
                               autocomplete="off">
                        <img src="{{ asset('profile_theme') }}/assets/images/calendar.png"
                             alt="calendar">
                    </div>
                </div>
                <div class="col-md-4 grid-margin">
                    <button type="submit"
                            class="btn btn-gradient-info btn-rounded btn-block"
                            style="min-height: 55px">Показать
                    </button>
                </div>
            </div>
        </form>
        
        <form action="">
            <div class="row">
                <div class="col-md-8 grid-margin">
                    <div class="inline">
                        <label for="itemsPerPageSelect">Показывать
                            по</label>
                        <select class="form-control form-control-lg per-page"
                                id="itemsPerPageSelect" onchange="window.location= pageUrl + '?per_page=' + this.value">
                            <option value="10" {{ (session()->get('per_page') == 10) ? 'selected' : '' }}>10</option>
                            <option value="20" {{ (session()->get('per_page') == 20) ? 'selected' : '' }}>20</option>
                            <option value="50" {{ (session()->get('per_page') == 50) ? 'selected' : '' }}>50</option>
                        </select>
                        
                        <a href="{{ route('enrollment', ['lang' => $lang, 'period' => 'whole']) }}" class="sort-transaction {{ (request()->period == 'whole') ? 'active-sort' : '' }}">За весь
                            период
                        </a>
                        <a href="{{ route('enrollment', ['lang' => $lang, 'period' => 'week']) }}" class="sort-transaction {{ (request()->period == 'week') ? 'active-sort' : '' }}">За
                            неделю
                        </a>
                        <a href="{{ route('enrollment', ['lang' => $lang, 'period' => 'month']) }}" class="sort-transaction {{ (request()->period == 'month') ? 'active-sort' : '' }}">За
                            месяц
                        </a>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group" style="justify-content: space-between; display: flex;">
                        <input type="text" class="form-control"
                               id="rand" 
                               placeholder="Поиск по номеру транзакции"
                               value="{{ request()->rand }}" 
                               autocomplete="off" />
                        <button type="button" 
                                class="btn btn-gradient-info" style="margin-left: 10px;"
                                onclick="window.location= pageUrl + '?rand=' + getElementById('rand').value">
                                <i class="fa fa-search" aria-hidden="true"></i>
                        </button>
                    </div>
                </div>
            </div>
        </form>

        @if(request()->from or request()->to or request()->rand)
            <div class="row">
                <div class="col-md-12">
                    <a href="{{ route('enrollment', ['lang' => $lang]) }}" class="btn btn-gradient-danger">Сбросить</a>
                </div>
            </div>
        @endif
         
    @endif
    
    <div class="row">

        @if($tips->count())
            <div class="col-md-12 grid-margin table-history">
                <table class="history">
                    <thead>
                        <tr>
                            <td>Номер транзакции</td>
                            <td>Дата зачисления <!-- <i class="mdi mdi-chevron-down"></i> --></td>
                            <td>Сумма <!-- <i class="mdi mdi-chevron-down"> --></i></td>
                            <td>Комиссия <!-- <i class="mdi mdi-chevron-down"></i> --></td>
                            <td>Заработано <!-- <i class="mdi mdi-chevron-down"></i> --></td>
                            <td>Способ оплаты <!-- <i class="mdi mdi-chevron-down"></i> --></td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($tips as $tip)
                            <tr class="{{ $tip->open ? 'open-tr' : '' }}">
                                <td>{{ $tip->rand }}</td>
                                <td>{{ $tip->created_at->format('d.m.Y H:i') }}</td>
                                <td>{{ $tip->amount }} Р</td>
                                <td>0 P</td>
                                <td>{{ $tip->amount }} P</td>
                                <td><img src="{{ asset('profile_theme') }}/assets/images/dashboard/visa.png" alt="visa"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div> 
        
            <div class="col-md-6 align-center"> 
                <span>Показано  {{ $tips->count() }} из {{ $tips->total() }}</span>
            </div>
            <div class="col-md-6">  
                {{ $tips->appends(request()->input())->links() }} 
            </div>
        @else
            <div class="col-12">
                <span class="d-flex align-items-center purchase-popup" style="justify-content: space-between;">
                  <p>Нет зачислений</p>  
                </span>
            </div>
        @endif
    </div>
            
@stop

