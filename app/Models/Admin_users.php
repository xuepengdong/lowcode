<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin_users extends Model
{
    use HasFactory;

    protected  $table = 'admin_users';

    public function database_tables_creator()
    {
        return $this->hasMany(Database_tables::class, 'creator_id');
    }

    public function database_tables_modifier()
    {
        return $this->hasMany(Database_tables::class, 'modifier_id');
    }
}
