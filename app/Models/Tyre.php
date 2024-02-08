<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Tyre extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'car_number',
        'model',
        'size',
        'quantity',
        'hasRim',
        'status',
        'storage_id',
        'observations',
    ];

    public const STATUS = [
        0 => 'Preluate de la Client',
        1 => 'Depozitate',
        2 => 'Predate la Client',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class);
    }

    public function getStatusAttribute($value)
    {
        return self::STATUS[$value] ?? "Unknown";
    }
}
