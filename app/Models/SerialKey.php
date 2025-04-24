<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialKey extends Model {
    use HasFactory;

    protected $fillable = ['serial_code', 'is_used', 'validation_key', 'expires_at'];
}
