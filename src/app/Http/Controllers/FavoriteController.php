<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Item;


class FavoriteController extends Controller
{
    public function toggle(Item $item)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'ログインが必要です。');
        }

        $user = auth::user();

        if ($user->favorites()->where('item_id', $item->id)->exists()) {
            $user->favorites()->detach($item->id); // お気に入り解除
        } else {
            $user->favorites()->attach($item->id); // お気に入り追加
        }

        return redirect()->back();
    }
}
