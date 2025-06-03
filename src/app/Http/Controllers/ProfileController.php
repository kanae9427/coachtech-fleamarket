<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use App\Http\Requests\AddressRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Profile;
use App\Models\User;

class ProfileController extends Controller
{
    public function show()
    {
        $user = auth()->user();
        return view('profile', compact('user'));
    }

    public function store(AddressRequest $addressRequest, ProfileRequest $profileRequest)
    {
        $user = auth()->user();

        // プロフィールアイコンの処理
        if ($profileRequest->hasFile('icon')) {
            $path = $profileRequest->file('icon')->store('icons', 'public');
            $path = str_replace('public/', 'storage/', $path);
        } else {
            $path = null;
        }

        // ユーザーデータを更新
        $user->update([
            'profile_name' => $addressRequest->profile_name,
            'postal_code' => $addressRequest->postal_code,
            'address' => $addressRequest->address,
            'building_name' => $addressRequest->building_name,
            'icon' => $path,
        ]);

        return redirect()->route('mypage.show');
    }

    public function update(AddressRequest $addressRequest, ProfileRequest $profileRequest)
    {
        $user = auth()->user();
        $data = [];

        // 名前・住所情報の更新
        if ($addressRequest->filled('profile_name')) {
            $data['profile_name'] = $addressRequest->profile_name;
        }
        if ($addressRequest->filled('postal_code')) {
            $data['postal_code'] = $addressRequest->postal_code;
        }
        if ($addressRequest->filled('address')) {
            $data['address'] = $addressRequest->address;
        }
        if ($addressRequest->filled('building_name')) {
            $data['building_name'] = $addressRequest->building_name;
        }

        // アイコンの処理
        if ($profileRequest->hasFile('icon')) {
            if ($user->icon) {
                Storage::disk('public')->delete($user->icon);
            }
            $data['icon'] = $profileRequest->file('icon')->store('icons', 'public');
        }

        // 更新処理
        if (!empty($data)) {
            $user->update($data);
        }

        return redirect()->route('mypage.show')->with('success', 'プロフィールを更新しました！');
    }
}
