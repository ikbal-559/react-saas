<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeatureResource;
use App\Http\Resources\PackageResource;
use App\Models\Feature;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Stripe\StripeClient;

class CreditController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        $features = Feature::where('active'. true)->get();
        return inertia("Credit/Index",[
           'packages' => PackageResource::collection($packages),
           'features' => FeatureResource::collection($features),
            'success' => session('success'),
            'error' => session('error'),
        ]);

    }

    public function buyCredits(Package $package)
    {
        $strip = new StripeClient(env('STRIPE_SECRET_KEY'));

        $checkoutSession = $strip->checkout->sessions->create([
            'line_items' => [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $package->name. ' - '. $package->credits. ' Credits',
                    ],
                    'unit_amount' => $package->price * 100
                ],
                'quantity' => 1
            ],
            'mode' => 'payment',
            'success_url' => route('credit.success', [], true),
            'cancel_url' => route('credit.cancel', [], true),
        ]);
        Transaction::create([
           'status' => 'pending',
           'price' => $package->price,
           'credits' => $package->credits,
           'session_id' => $checkoutSession->id,
           'user_id' => auth()->id(),
           'package' => $package->id
        ]);

        return redirect($checkoutSession->success_url);


    }

    public function success()
    {

        return  to_route('credit.index')
            ->with('success', 'You have successfully bought new credits.');


    }

    public function cancel()
    {
        return  to_route('credit.index')
            ->with('error', 'There was an error in payment process.');

    }

    public function webhook()
    {

    }

}
