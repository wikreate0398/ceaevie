<?php

namespace App\Http\Controllers\Profile\Partner;

use Illuminate\Http\Request;
use App\Http\Controllers\Profile\EnrollmentController;

use App\Models\User;
use App\Models\Tips;
use App\Models\ProfileMenu;

class PartnerEnrollmentController extends EnrollmentController
{
    public function index()
    {
        $menu = ProfileMenu::where('route', 'enrollment')->first();

        $ids = $this->getUserIds();

        $tips = Tips::confirmed()
                    ->withPartnerPercent()
                    ->select('tips.*')
                    ->whereIn('id_user', $ids)
                    ->filter()
                    ->orderBy('tips.id', 'desc')
                    ->groupBy('tips.id')
                    ->paginate(self::getPerPage());

        exit(print_arr($tips->toArray()));

        return view('profile.partner.enrollment', compact(['tips', 'menu']));
    }

    private function getUserIds($ids = [])
    {
        $data = User::where('id', \Auth::id())->with('referrals.locationUsers')->first();

        foreach ($data->referrals as $referral){
            foreach ($referral->locationUsers as $user){
                $ids[] = $user->id;
            }
        }

        return array_unique(array_merge($ids, $data->referrals->pluck('id')->toArray()));
    }
}