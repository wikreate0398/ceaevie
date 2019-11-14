<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div
            class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">
                    Текущий баланс для вывода
                </h4>
                <h2>{{ Auth::user()->ballance }} P <span> / {{ setting('minimum_withdrawal') }} P</span></h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div
            class="card bg-gradient-info card-img-holder text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">
                    Заработано "чаевых" всего
                </h4>
                <h2> 
                    {{ sumTipAmount(\App\Models\Tips::confirmed()
                                                     ->selectRaw('amount, status, created_at, location_work_type, id_location, location_amount')
                                                     ->where(((\Auth::user()->type == 'admin') ? 'id_location' : 'id_user'), \Auth::id())
                                                     ->get(), Auth::user()->type, Auth::id()) }} Р
                </h2>
            </div>
        </div>
    </div>
    <div class="col-md-4 stretch-card grid-margin">
        <div
            class="card bg-gradient-success card-img-holder text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">
                    Заработано "чаевых" на этой неделе
                </h4>
                <h2>{{ sumTipAmount(\App\Models\Tips::confirmed(7)
                                                 ->selectRaw('amount, status, created_at, location_work_type, id_location, location_amount')
                                                 ->where(((\Auth::user()->type == 'admin') ? 'id_location' : 'id_user'), \Auth::id())
                                                 ->get(), Auth::user()->type, Auth::id()) }} Р</h2>
            </div>
        </div>
    </div>
</div>