<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function subfolders()
    {
        return $this->hasMany(Folder::class, 'parent_id');
    }
}
