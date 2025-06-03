<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentRequest;
use App\Models\Item;
use App\Models\Comment;

class CommentController extends Controller
{
    public function store(CommentRequest $request, Item $item)
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'ログインしてください');
        }

        $validated = $request->validated();

        Comment::create([
            'user_id' => auth()->id(),
            'item_id' => $item->id,
            'content' => $validated['content'],
        ]);

        return redirect()->back()->with('status', 'コメントを投稿しました');
    }
}
