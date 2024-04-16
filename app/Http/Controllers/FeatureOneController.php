<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeatureResource;
use App\Models\Feature;
use App\Models\UsedFeature;
use Illuminate\Http\Request;

class FeatureOneController extends Controller
{
    public ?Feature $feature = null;


    public function __construct()
    {
        $this->feature = Feature::where('route_name', 'feature-one.index')
            ->where('active', true)
            ->firstOrFail();
    }

    public function index()
    {
        return inertia('FeatureOne/Index', [
            'feature' => new FeatureResource($this->feature),
            'answer' => session('answer')
        ]);

    }

    public function calculator (Request $request)
    {
        $user = $request->user();
        if($user->available_credits < $this->feature->required_credits){
            return back();
        }

        $data = $request->validate([
            'number1' => ['required', 'numeric'],
            'number2' => ['required', 'numeric'],
        ]);

        $number1 = (float) $data['number1'];
        $number2 = (float) $data['number2'];

        $user->decreaseCredits($this->feature->required_credits);

        UsedFeature::create([
            'feature_id' => $this->feature->id,
            'user_id' => $user->id,
            'credits' => $this->feature->required_credits,
            'data' => $data,
        ]);
        return to_route('feature-one.index')->with('answer', $number1 + $number2);
    }

}
