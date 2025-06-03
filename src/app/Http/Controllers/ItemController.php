<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $viewType = $request->query('tab', 'all'); // デフォルトは 'all'
        $search = $request->query('search', ''); // 検索クエリ取得

        if ($viewType === 'mylist' && Auth::check()) {
            $query = Auth::user()->favorites();
        } else {
            $query = Item::query();
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
        $comments = $item->comments()->latest()->get();

        return view('item', compact('item', 'favoriteCount', 'commentCount' , 'comments'));
    }

}
