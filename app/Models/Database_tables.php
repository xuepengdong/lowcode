<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Database_tables extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected  $table = 'database_tables';


    public function creator()
    {
        return $this->belongsTo(Admin_users::class, 'creator_id', 'id');
    }

    public function modifier()
    {
        return $this->belongsTo(Admin_users::class, 'modifier_id', 'id');
    }

    public function fields()
    {
        return $this->hasMany(Field::class, 'modelid');
    }

    public function setCreated_atAttribute($value){
        if($this->created_at){
            return;
        }
        $this->attributes['created_at'] = $value;
    }
}
