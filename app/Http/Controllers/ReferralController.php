<?php

namespace App\Http\Controllers;

use App\Data\Referral\ReferralData;
use App\Models\Referral;
use App\Pages\ReferralsIndexPage;
use Illuminate\Http\Request;
use Spatie\LaravelData\PaginatedDataCollection;

class ReferralController
{
    public function index()
    {
        $referrals = Referral::orderBy('created_at', 'desc')->paginate(20);

        return inertia('referrals/index/page', new ReferralsIndexPage(
            referrals: ReferralData::collect($referrals, PaginatedDataCollection::class)
        ));
    }

    public function create(string $code, Request $request)
    {
        if (strlen($code) < 1 || strlen($code) > 24) {
            return redirect()->away('https://2004.lostcity.rs');
        }

        Referral::create([
            'code' => $code,
            'ip' => $request->ip(),
        ]);

        return redirect()->away('https://2004.lostcity.rs');
    }
}
