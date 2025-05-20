<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers'; // Set the correct table name

    protected $fillable = [
        'name', 
        'email', 
    ];

    public $timestamps = true; // Enable automatic timestamps if needed

    public function projects()
    {
        return $this->hasMany(Project::class);
    }
}
