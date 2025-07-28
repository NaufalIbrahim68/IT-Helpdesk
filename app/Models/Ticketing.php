<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Ticketing extends Model
{
    use HasFactory;

    protected $table = 'ticketings';
    
    protected $primaryKey = 'ticketing_id';


    protected $fillable = [
         'id_user',
    'subject',
    'description',
    'status',
    'name',
    'assigned_to',
    'npk',
    'created_at',
    'updated_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_ON_THE_LIST = 'on the list';
   const STATUS_IN_PROGRESS = 'in progress';
    const STATUS_SOLVED = 'solved';
    const STATUS_REJECTED = 'reject';

    // Status options
    public static function getStatusOptions()
    {
        return [
            self::STATUS_PENDING => 'Belum Ditangani',
            self::STATUS_IN_PROGRESS => 'Sedang Ditangani',
            self::STATUS_SOLVED => 'Sudah Selesai',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_ON_THE_LIST => 'Dalam Daftar',
        ];
    }

    // Accessor for status label
    public function getStatusLabelAttribute()
    {
        $statusOptions = self::getStatusOptions();
        return $statusOptions[$this->status] ?? 'Unknown';
    }

    // Accessor for status color
    public function getStatusColorAttribute()
    {
        switch ($this->status) {
            case self::STATUS_PENDING:
                return 'bg-yellow-100 text-yellow-800';
            case self::STATUS_IN_PROGRESS:
            return 'bg-blue-100 text-blue-800';
            case self::STATUS_SOLVED:
                return 'bg-green-100 text-green-800';
            case self::STATUS_REJECTED:
                return 'bg-red-100 text-red-800';
            case self::STATUS_ON_THE_LIST:
                return 'bg-purple-100 text-purple-800';
            default:
                return 'bg-gray-100 text-gray-800';

        }
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeOpen($query)
    {
        return $query->where('status', self::STATUS_IN_PROGRESS);
    }

    public function scopeSolved($query)
    {
        return $query->where('status', self::STATUS_SOLVED);
    }

    public function scopeReject($query)
{
    return $query->where('status', self::STATUS_REJECTED);
}

public function scopeOnTheList($query)
{
    return $query->where('status', self::STATUS_ON_THE_LIST);
}

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucfirst($value);
    }

    public function setSubjectAttribute($value)
    {
        $this->attributes['subject'] = ucfirst($value);
    }

    /**
     * Relasi ke User sebagai pembuat ticket
     */
   public function user()
{
    return $this->belongsTo(User::class, 'id_user', 'id_user');
}


    /**
     * Relasi ke User yang ditugaskan
     */
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id_user');
    }
}
