<div class="row">
    <div class="col-md-4 stretch-card grid-margin">
        <div
            class="card bg-gradient-danger card-img-holder text-white">
            <div class="card-body">
                <h4 class="font-weight-normal mb-3">
                    Текущий баланс для вывода
                </h4>
                <h2>{{ \App\Models\Tips::forWithdraw()->sum('amount') }} P <span> / 3000 P</span></h2>
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
                <h2>{{ \App\Models\Tips::confirmed()->sum('amount') }} Р</h2>
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
                <h2>{{ \App\Models\Tips::confirmed(7)->sum('amount') }} Р</h2>
            </div>
        </div>
    </div>
</div>