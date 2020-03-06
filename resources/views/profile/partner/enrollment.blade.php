@extends('layouts.personal_profile')

@section('content')
    <script>
        var pageUrl = '{{ route('enrollment', ['lang' => $lang]) }}';
    </script>
    <div class="page-header">
        <h3 class="page-title">
    <span
            class="page-title-icon bg-gradient-danger text-white mr-2">
      {!! $menu["icon"] !!}
    </span> {{ $menu["name_$lang"] }} </h3>
    </div>

    @include('profile.utils.cards')

    @if($tips->count() or (request()->from or request()->to or request()->rand))
        <form action="{{ route('enrollment', ['lang' => $lang]) }}">
            <div class="row">
                <div class="col-md-12">
                    <h4 class="title">Отобразить платежи за период</h4>
                </div>

                <div class="col-md-4 grid-margin">
                    <div class="input-date">
                        <input type="text" class="datepicker"
                               placeholder="дд.мм.гг"
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
                               placeholder="дд.мм.гг"
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

        <div class="row">
            @if($tips->count())
                <div class="col-md-12 grid-margin table-history">
                    <table class="history eq-table-cell">
                        <thead>
                        <tr>
                            <td class="ac" style="width: 20%;">№ транзакции</td>
                            <td class="ac" style="width: 20%;">Дата зачисления</td>
                            <td class="ac" style="width: 20%;">Официант</td>
                            <td class="ac" style="width: 20%;">Сумма</td>
                            <td class="ac" style="width: 20%;">Заработано</td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($tips as $tip)
                            <tr class="{{ $tip->open ? 'open-tr' : '' }}">
                                <td class="ac">{{ $tip->rand }}</td>
                                <td class="ac">{{ $tip->created_at->format('d.m.Y H:i') }}</td>
                                <td class="ac">{{ $tip->user->name }} {{ $tip->user->lastname }}</td>
                                <td class="ac">{{ $tip->total_amount }} Р</td>
                                <td class="ac">
                                    {{ percent($tip->total_amount, @$tip['percents'][0]->percent) }} Р
                                </td>
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
                <div class="col-12" style="margin-top: 20px;">
                <span class="d-flex align-items-center purchase-popup" style="justify-content: space-between;">
                  <p>Нет зачислений</p>
                </span>
                </div>
            @endif
        </div>

    @else
        <div class="row">
            <div class="col-12">
                <span class="d-flex align-items-center purchase-popup" style="justify-content: space-between;">
                  <p>Нет зачислений</p>
                </span>
            </div>
        </div>
    @endif
@stop

