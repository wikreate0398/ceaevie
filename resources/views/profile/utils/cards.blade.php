@if(in_array(Auth::user()->type, ['user', 'admin']))
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
                        {{ \Ballance::getUserBallance(Auth::id(), Auth::user()->type) }} Р
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
                    <h2> 
                        {{ \Ballance::getUserBallance(Auth::id(), Auth::user()->type, 7) }} Р
                    </h2>
                </div>
            </div>
        </div>
    </div>
@endif