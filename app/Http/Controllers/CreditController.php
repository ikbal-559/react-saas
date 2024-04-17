<?php

namespace App\Http\Controllers;

use App\Http\Resources\FeatureResource;
use App\Http\Resources\PackageResource;
use App\Models\Feature;
use App\Models\Package;
use App\Models\Transaction;
use Illuminate\Support\Facades\Log;
use Stripe\StripeClient;
use Exception;

class CreditController extends Controller
{
    public function index()
    {
        $packages = Package::all();
        $features = Feature::where('active', true)->get();
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
                'success_url' =>  route('credit.success' ),
                'cancel_url' => route('credit.cancel' ),
                'line_items' => [
                    [
                        'price_data' => [
                            'currency' => 'usd',
                            'unit_amount' => $package->price * 100,
                            'product_data' => [
                                'name' => $package->name. ' - '. $package->credits. ' Credits',
                            ]
                        ],
                        'quantity' => 1,
                    ]
                ],
                'mode' => 'payment',
            ]
        );
        Transaction::create([
           'status' => 'pending',
           'price' => $package->price,
           'credits' => $package->credits,
           'session_id' => $checkoutSession->id,
           'user_id' => auth()->id(),
           'package_id' => $package->id
        ]);
        return redirect($checkoutSession->url);
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

    /**
     * @throws Exception
     */
    public function webhook()
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_KEY');
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $endpoint_secret
            );
            // Handle the event
            switch ($event->type) {
                case 'payment_intent.succeeded':
                    $session = $event->data->object;
                    $transaction = Transaction::with('user')->where('session_id', $session->id)->first();
                    if ($transaction && $transaction->status === 'pending') {
                        $transaction->status = 'paid';
                        $transaction->save();
                        $transaction->user->available_credits += $transaction->credits;
                        $transaction->user->save();
                    }
                    return response(200);
                default:
                    throw new Exception($event->type);
            }
        } catch (\UnexpectedValueException $e) {
            // Invalid payload
            return response(400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            // Invalid signature
            return response(400);
        } catch (Exception $e) {
            // Invalid signature
            Log::info($e->getMessage());
            return response(400);
        }
    }

}
