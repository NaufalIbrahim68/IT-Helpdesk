<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrioritasTicket extends Model
{
    protected $table = 'prioritas_ticket';
      protected $fillable = [
        'id_user', 'name', 'npk', 'department', 'added_at',
    ];

    public function ticket()
{
    return $this->belongsTo(\App\Models\Ticketing::class, 'id_user', 'id_user');
}

}
