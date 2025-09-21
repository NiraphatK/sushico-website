<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreSettingModel extends Model
{
    use HasFactory;

    protected $table = 'store_settings';

    protected $primaryKey = 'settings_id';

    public $incrementing = false; // singleton row
    protected $keyType = 'int';

    protected $fillable = [
        'timezone',
        'cut_off_minutes',
        'grace_minutes',
        'buffer_minutes',
        'slot_granularity_minutes',
        'default_duration_minutes',
        'open_time',
        'close_time',
    ];
}
