<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReservationModel extends Model
{
    use HasFactory;
    protected $table = 'reservations';

    protected $primaryKey = 'reservation_id';

    protected $fillable = [
        'user_id',
        'table_id',
        'party_size',
        'seat_type',
        'start_at',
        'end_at',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function table()
    {
        return $this->belongsTo(TableModel::class, 'table_id');
    }
}