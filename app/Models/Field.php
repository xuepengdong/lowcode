<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use HasFactory;
    protected  $table = 'field';

    protected $guarded = [];

    public function database_tables()
    {
        return $this->belongsTo(Database_tables::class, 'modelid');
    }

    public function setCreated_atAttribute($value){
        if($this->created_at){
            return;
        }
        $this->attributes['created_at'] = $value;
    }
}
