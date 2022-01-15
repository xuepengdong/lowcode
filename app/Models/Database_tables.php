<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Database_tables extends Model
{
    use HasFactory;

    protected  $table = 'database_tables';

    public function creator()
    {
        return $this->belongsTo(Admin_users::class, 'creator_id');
    }

    public function modifier()
    {
        return $this->belongsTo(Admin_users::class, 'modifier_id');
    }
}
