<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use App\Models\Reservation;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $reservationId = $request->input('reservation_id');

        Stripe::setApiKey(env('STRIPE_SECRET'));
        $pay = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'unit_amount' => 500,
                    'product_data' => [
                        'name' => '席料'
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('success', ['reservation_id' => $reservationId]),
            'cancel_url' => route('cancel'),
        ]);
        return redirect($pay->url);
    }

    public function success(Request $request)
    {
        $reservationId = $request->query('reservation_id');

        $reservation = Reservation::find($reservationId);
        $reservation->is_paid = true;
        $reservation->save();

        session()->flash('success', '決済が完了しました');
        return redirect('/mypage')->with('pay', 'success');
    }

    public function cancel()
    {
        session()->flash('fail', '決済できませんでした');
        return redirect('/mypage')->with('pay', 'cancel');
    }
}
