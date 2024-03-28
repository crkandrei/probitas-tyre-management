<?php

namespace App\Models;

use Carbon\Carbon;
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
        'checkin_date'
    ];

    public const STATUS = [
        0 => 'Preluate de la Client',
        1 => 'Depozitate',
        2 => 'Predate la Client',
    ];

    protected $casts = [
        'checkin_date' => 'datetime',
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

    /**
     * Get the check-in date in the format suitable for datetime-local input.
     *
     * @return string|null
     */
    public function getCheckinDateAttribute($value)
    {
        // Check if the date is not null
        if ($value !== null) {
            // Convert to Carbon instance for easy formatting
            $date = Carbon::parse($value);

            // Return the date in 'Y-m-d\TH:i' format
            return $date->format('Y-m-d');
        }

        return null; // Return null if there's no date
    }
}
