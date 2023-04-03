<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Categorie extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "code",
        "intitule",
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($categorie) {
            $categorie->code .= 'CAT-' . $categorie->id;
            $categorie->save();
        });
    }

    public function Article()
    {
        return $this->hasMany(Article::class);
    }
}
