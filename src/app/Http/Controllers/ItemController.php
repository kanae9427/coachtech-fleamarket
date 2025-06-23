<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $viewType = auth()->check() ? $request->query('tab', 'mylist') : 'all'; // デフォルトは 'all'
        $search = $request->query('search', ''); // 検索クエリ取得
        $user = auth()->user();
        $user_id = auth()->id();

        if ($viewType === 'mylist' && auth()->check()) {
            $query = $user->favorites();
        } else {
            $query = Item::where('user_id', '!=', $user_id);
        }

        // 検索がある場合、部分一致フィルタを適用
        if (!empty($search)) {
            $query->where('name', 'LIKE', "%{$search}%");
        }

        $items = $query->get();

        return view('index', compact('items', 'viewType', 'search'));
    }

    public function show(Item $item)
    {
        $favoriteCount = $item->favoritedBy() -> exists() ? $item->favoritedBy()->count() : 0;
        $commentCount = $item->comments()->count();
        $comments = $item->comments()->with('user')->latest()->get();

        return view('item', compact('item', 'favoriteCount', 'commentCount' , 'comments'));
    }

}
