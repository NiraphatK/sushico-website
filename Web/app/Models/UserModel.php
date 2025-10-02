<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable; // ใช้ Auth ได้
use Illuminate\Notifications\Notifiable;

class UserModel extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'password_hash',
        'role',
        'is_active',
    ];

    // ซ่อนฟิลด์ที่ไม่ต้องการให้แสดง
    protected $hidden = [
        'password_hash',
        'remember_token',
    ];

    // ระบุให้ Laravel รู้ว่าใช้ password column ไหน
    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // ความสัมพันธ์: user มีหลาย reservation
    public function reservations()
    {
        return $this->hasMany(ReservationModel::class, 'user_id');
    }
}
