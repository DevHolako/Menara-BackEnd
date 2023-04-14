<?php

namespace App\Models;

use App\Models\Devi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "raison_social",
        "ice",
        "rc",
        "type",
        "categorie",
    ];
    public function devi()
    {
        return $this->hasMany(Devi::class);
    }
}
