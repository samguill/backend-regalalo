<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SlideElement extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $fillable = [
        'id',
        "content",
        "type",
        "url",
        "position_x",
        "position_y",
        "animation",
        'slide_id'
    ];
}
