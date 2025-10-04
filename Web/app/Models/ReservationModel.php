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

    // แปลงค่าเป็น datetime object อัตโนมัติ
    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    // ช่วยกรองของ user ปัจจุบันเวลา query
    public function scopeOfUser($q, $userId)
    {
        return $q->where('user_id', $userId);
    }

    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id');
    }

    public function table()
    {
        return $this->belongsTo(TableModel::class, 'table_id');
    }
}
