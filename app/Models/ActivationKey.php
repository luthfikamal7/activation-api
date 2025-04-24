<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialKey extends Model
{
    use HasFactory;

    protected $table = 'serial_keys'; // Set the correct table name

    protected $fillable = [
        'serial_code', 
        'is_used', 
        'validation_key', 
        'expires_at', 
        'created_at', 
        'updated_at'
    ];

    public $timestamps = true; // Enable automatic timestamps if needed
}
