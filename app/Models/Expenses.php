<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Expenses extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_id',
        'date_expenses',
        'value',
        'description'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
