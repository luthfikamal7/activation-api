<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SerialKey extends Model {
    use HasFactory;

    protected $fillable = [
        'serial_code', 
        'is_used', 
        'validation_key', 
        'expires_at', 
        'start_at', 
        'duration',
        'customer_id', 
        'project_id',
    ];
    
    protected $casts = [
        'is_used' => 'boolean',
        'expires_at' => 'datetime',
        'start_at' => 'datetime',
    ];

    public $timestamps = true;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    
}
