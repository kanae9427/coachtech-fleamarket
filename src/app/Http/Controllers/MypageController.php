<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Purchase;

class MypageController extends Controller
{
    public function show(Request $request)
    {
        $user = auth()->user();
        $viewType = $request->query('view', 'items'); // デフォルトは「出品商品」

        if ($viewType === 'purchases') {
            // ✅ 購入した商品の取得（Purchase テーブルから取得）
            $items = Purchase::where('user_id', $user->id)
                ->with('item') // ✅ `item_id` と関連する商品を取得
                ->get()
                ->pluck('item'); // ✅ 商品リストを抽出
        } else {
            // ✅ 出品した商品の取得
            $items = Item::where('user_id', $user->id)->get();
        }


        return view('mypage', compact('user', 'items', 'viewType'));
    }
}
