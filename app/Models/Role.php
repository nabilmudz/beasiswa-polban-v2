<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $table = 'role';
    protected $fillable = ['role_name'];

    public function reviewer()
    {
        return $this->hasMany(Reviewer::class, 'role_id', 'id');
    }
}
