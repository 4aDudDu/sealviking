<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    // Tambahkan baris ini biar datanya bisa disimpan
    protected $fillable = [
        'title', 
        'description', 
        'content', 
        'image', 
        'is_hot', 
        'published_at'
    ];
}
