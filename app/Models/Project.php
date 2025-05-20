<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $table = 'projects';

    protected $fillable = ['project_id', 'name', 'customer_id'];

    public $timestamps = true;

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
