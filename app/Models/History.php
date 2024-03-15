<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    protected $table = 'tyre_history';

    protected $fillable = ['client_id', 'tyre_id', 'action', 'action_date'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function tyre()
    {
        return $this->belongsTo(Tyre::class);
    }

    public function getActionDateAttribute($value)
    {
        return date('d/m/Y H:i', strtotime($value));
    }
}
