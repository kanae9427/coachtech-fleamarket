<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PurchaseRequest;
use App\Models\Item;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function show(Request $request, $item_id)
    {
        $item = Item::findOrFail($item_id);
        $payment_method = $request->query('payment_method', '未選択');
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

        // 商品を取得
        $item = Item::findOrFail($validated['item_id']);

        // すでに購入されていたらエラーを出す
        if ($item->sold) {
            return redirect()->back()->with('error', 'この商品はすでに売り切れです');
        }


        // 購入処理の記録
        $purchase = Purchase::create([
            'user_id' => auth()->id(),
            'item_id' => $validated['item_id'],
            'shipping_postal_code' => session('shipping_postal_code'),
            'shipping_address' => session('shipping_address'),
            'shipping_building_name' => session('shipping_building_name'),
            'payment_method' => $validated['payment_method'],
            'status' => 'pending',
        ]);

        // 商品を「sold」に更新
        $item->update(['sold' => true]);

        return redirect()->route('mypage.show')->with('success', '購入が完了しました!');
    }
}
