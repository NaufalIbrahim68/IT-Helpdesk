<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InputData extends Model
{
    use HasFactory;

    protected $table = 'input_data'; // nama tabel

   protected $fillable = [
    'user_id',
    'name',
    'npk',
    'department',
    'subject',
    'description',
];

 public function user()
{
      return $this->belongsTo(User::class, 'user_id', 'id_user');
}
}
