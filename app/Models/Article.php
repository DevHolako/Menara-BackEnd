<?php

namespace App\Models;

use App\Models\Categorie;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "code",
        "categorie_code",
        "designtion",
        "prix",
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($article) {
            $article->code .= 'ART-' . $article->id;
            $article->save();
        });
    }

    public function Categorie()
    {
        return $this->belongsTo(Categorie::class, "categorie_code");
    }

    public  function devi(){
        return $this->belongsTo(Devi::class)->withPivot("qty","prix","total");
    }
}
