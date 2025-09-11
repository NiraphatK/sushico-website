<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TableModel extends Model
{
    use HasFactory;

    protected $table = 'tables';

    protected $primaryKey = 'table_id';

    protected $fillable = [
        'table_number',
        'capacity',
        'seat_type',
        'is_active',
    ];

    // ความสัมพันธ์: โต๊ะมีได้หลาย reservation
    public function reservations()
    {
        return $this->hasMany(ReservationModel::class, 'table_id');
    }
}
