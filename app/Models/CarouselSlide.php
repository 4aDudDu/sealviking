<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CarouselSlide extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'image',
        'subtitle',
        'box1_label',
        'box1_value',
        'box2_label',
        'box2_value',
        'box3_label',
        'box3_value',
        'order',
        'is_active',
    ];
}
