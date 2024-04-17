<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserFeatureResource;
use App\Models\UsedFeature;
use Inertia\Inertia;

class DashboardController extends Controller
{

    public function index()
    {
        $userFeatures = UsedFeature::with('feature')->where('user_id', auth()->id())->latest()->paginate();
        return Inertia::render('Dashboard', [
           'usedFeatures' => UserFeatureResource::collection($userFeatures)
        ]);

    }




}
