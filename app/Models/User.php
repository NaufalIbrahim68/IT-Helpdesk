<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;



/**
 * App\Models\User
 *
 * @property int $id_user
 * @property string $name
 * @property string $role
 * @property string $npk
 * @property string $department
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id_user'; // ✅ tambahkan ini

    protected $fillable = [
        'name',
        'email',
        'password',
        'npk',
        'role',
        'department',
    ];

    

     

    /**
     * The attributes that should be hidden for serialization.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'password' => 'hashed', // oke, ini Laravel 10+ syntax, bagus
    ];

    /**
     * Override agar Laravel pakai npk sebagai credential utama
     */
    public function getAuthIdentifierName()
    {
        return 'npk';
    }

    // * @return \Illuminate\Database\Eloquent\Relations\HasMany

    public function ticketings()
{
    return $this->hasMany(\App\Models\Ticketing::class, 'id_user', 'id_user');
}

    


}
