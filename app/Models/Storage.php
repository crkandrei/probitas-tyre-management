<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storage extends Model
{
    use HasFactory;

    protected $fillable = [
       'row',
        'column',
        'shelf',
        'observations',
        'deposit_id',
    ];

    public function tyre()
    {
        return $this->hasOne(Tyre::class);
    }
}
