<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\User;
use App\Models\Comment;
use App\Models\Purchase;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'image',
        'condition',
        'description',
        'user_id',
        'sold'
    ];

    protected $casts = [
        'sold' => 'boolean',
    ];


    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_items');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
}
