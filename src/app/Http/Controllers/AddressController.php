<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddressRequest;
use App\Models\Item;

class AddressController extends Controller
{
    public function edit($item_id)
    {
        $item = Item::findOrFail($item_id);
        $user = auth()->user();

        return view('address_edit', compact('item', 'user'));
    }

    public function update(Request $request, $item_id)
    {
        session([
            'shipping_postal_code' => $request->shipping_postal_code,
            'shipping_address' => $request->shipping_address,
            'shipping_building_name' => $request->shipping_building_name,
        ]);

        return redirect()->route('purchase.show' , ['item_id' => $item_id])->with('success', '配送先を更新しました！');
    }
}
