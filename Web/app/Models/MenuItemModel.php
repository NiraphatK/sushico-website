<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuItemModel extends Model
{
    use HasFactory;

    protected $table = 'menu_items';

    protected $primaryKey = 'menu_id';

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_path',
        'is_active',
        'detail'
    ];
}
