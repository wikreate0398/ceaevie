@extends('layouts.personal_profile')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
    <span
        class="page-title-icon bg-gradient-danger text-white mr-2">
      <i class="mdi mdi-chart-line"></i>
    </span> История зачислений </h3>
    </div>

    @include('profile.utils.cards')
     
    <form action="">
        <div class="row">
            <div class="col-md-12">
                <h4 class="title">Отобразить платежи за период</h4>
            </div>
            
            <div class="col-md-4 grid-margin">
                <div class="input-date">
                    <input type="text" class="datepicker"
                           placeholder="mm/dd/yy">
                    <img src="{{ asset('profile_theme') }}/assets/images/calendar.png"
                         alt="calendar">
                </div>
            </div>
            <div class="col-md-4 grid-margin">
                <div class="input-date">
                    <input type="text" class="datepicker"
                           placeholder="mm/dd/yy">
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
                            id="itemsPerPageSelect">
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    
                    <button type="button" class="sort-transaction">За весь
                        период
                    </button>
                    <button type="button" class="sort-transaction">За
                        неделю
                    </button>
                    <button type="button" class="sort-transaction">За
                        месяц
                    </button>
                </div>
            
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <input type="text" class="form-control"
                           placeholder="Поиск по номеру транзакции"/>
                </div>
            </div>
        </div>
    </form>
    
    <div class="row">
        <div class="col-md-12 grid-margin table-history">
            <table class="history">
                <thead>
                    <tr>
                        <td>Номер транзакции</td>
                        <td>Дата зачисления <i class="mdi mdi-chevron-down"></i></td>
                        <td>Сумма <i class="mdi mdi-chevron-down"></i></td>
                        <td>Комиссия <i class="mdi mdi-chevron-down"></i></td>
                        <td>Заработано <i class="mdi mdi-chevron-down"></i></td>
                        <td>Способ оплаты <i class="mdi mdi-chevron-down"></i></td>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>8451233</td>
                        <td>22.12.2019</td>
                        <td>100 Р</td>
                        <td>5 P</td>
                        <td>95 P</td>
                        <td><img src="{{ asset('profile_theme') }}/assets/images/dashboard/visa.png" alt="visa"></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6 align-center">
            <span>Показано 10 из 16</span>
        </div>
        <div class="col-md-6">
            <ul class="pages">
                <li>
                    <a href="#"><i class="mdi mdi-chevron-left"></i></a>
                </li>
                <li class="active"><a href="#1">1</a></li>
                <li><a href="#2">2</a></li>
                <li>
                    <a href="#"><i class="mdi mdi-chevron-right"></i></a>
                </li>
            </ul>
        </div>
    </div>
            
@stop

