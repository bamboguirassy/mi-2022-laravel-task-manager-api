<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class Tache extends Model
{
    use HasFactory;

    protected $guarded = ['uid','id','user_id'];

    public static function boot()
    {
        parent::boot();

        self::creating(function ($tache) {
            $tache->uid = md5(uniqid());
            $tache->user_id = Auth::id();
        });

        self::created(function($tache) {
            // notifier l'admin par mail
            // de la création d'une nouvelle tache
        });
    }

    /**
     * Get the user that owns the Tache
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
