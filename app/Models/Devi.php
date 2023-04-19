<?php

namespace App\Models;

use App\Models\Article;
use App\Models\Client;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Devi extends Model
{
    use HasFactory, SoftDeletes;
    protected $guard_name = 'sanctum';

    protected $fillable = [
        "code",
        "date",
        "client_id",
        "total",
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($dev) {
            $dev->code .= 'DEV-' . $dev->id;
            $dev->save();
        });
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function article()
    {
        return $this->belongsToMany(Article::class)->withPivot('qty', 'prix', 'total')->withTimestamps();
    }
}
