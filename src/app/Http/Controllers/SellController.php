<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Item;
use App\Http\Requests\ExhibitionRequest;

class SellController extends Controller
{
    public function sell()
    {
        $categories = Category::all();
        return view('sell', compact('categories'));
    }

    public function store(ExhibitionRequest $request)
    {
        $path = $request->file('image')->store('public/images');

        $item = Item::create(array_merge(
            $request->except('category_id','image'),
            [
                'user_id' => auth()->id(),
                'image' => str_replace('public/', 'storage/', $path)
            ]
        ));
        $item->categories()->sync($request->category_id);

        return redirect()->route('mypage.show')->with('success', '商品を出品しました！');
    }

    public function show($item_id)
    {
        $item = Item::findOrFail($item_id);
        return view('item', compact('item'));
    }
}
