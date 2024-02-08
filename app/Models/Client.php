<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'telephone',
    ];

    public function tyres()
    {
        return $this->hasMany(Tyre::class);
    }

    public function history()
    {
        return $this->hasMany(History::class);
    }
}
