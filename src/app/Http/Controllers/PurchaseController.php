<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session;


class PurchaseController extends Controller
{
    public function show(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $payment_method = $request->query('payment_method', session('payment_method', '未選択'));
        session()->put('payment_method', $payment_method);
        switch ($payment_method) {
            case 'convenience_store':
                $payment_method_jp = 'コンビニ支払い';
                break;
            case 'credit_card':
                $payment_method_jp = 'カード支払い';
                break;
            default:
                $payment_method_jp = '未選択';
        }


        return view('purchase', compact('item', 'payment_method', 'payment_method_jp'));
    }

    public function store(PurchaseRequest $request)
    {
        $validated = $request->validated();

        $item_id = (int) $validated['item_id'];

        // 商品を取得
        $item = Item::findOrFail($item_id);

        // すでに購入されていたらエラーを出す
        if ($item->sold){
            return redirect()->back()->with('error', 'この商品は売り切れです');
        }

        $item->update(['sold' => true]);

        //Stripe APIを初期化
        Stripe::setApiKey(config('services.stripe.secret'));

        $paymentMethodType = $validated['payment_method'] === 'convenience_store' ? 'konbini' : 'card';

        //Stripe Checkoutセッション作成
        $session = Session::create([
            'payment_method_types' => [$paymentMethodType],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'jpy',
                    'product_data' => [
                        'name' => $item->name,
                    ],
                    'unit_amount' => $item->price ,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('purchase.success', ['item_id' => $item->id]),
            'cancel_url' => route('purchase.cancel'),
        ]);

        return redirect()->away($session->url); // Stripe決済画面へリダイレクト！
    }

    public function success($item_id)
    {
        $item = Item::findOrFail($item_id);

        // セッションにデータがない場合、登録済みの住所をセット
        session()->put('shipping_postal_code', session('shipping_postal_code', auth()->user()->postal_code));
        session()->put('shipping_address', session('shipping_address', auth()->user()->address));
        session()->put('shipping_building_name', session('shipping_building_name', auth()->user()->building_name));

        // 購入処理の記録
        Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'shipping_postal_code' => session('shipping_postal_code'),
            'shipping_address' => session('shipping_address'),
            'shipping_building_name' => session('shipping_building_name'),
            'payment_method' => 'credit_card',
            'status' => 'completed',
        ]);

        return redirect()->route('mypage.show')->with('success', '購入が完了しました!');
    }

    public function cancel()
    {

        $item = Item::where('sold', true)->first();
        if ($item) {
            $item->update(['sold' => false]);
        }

        return redirect()->route('purchase.show')->with('error', '決済がキャンセルされました。もう一度お試しください。');
    }

}
