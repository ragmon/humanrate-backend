<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'phone', 'name',
    ];

    protected $appends = [
        'is_user',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skills()
    {
        return $this->hasMany(Skill::class, 'user_id');
    }

    public function getIsUserAttribute()
    {
        return User::where('phone', $this->phone)->exists();
    }

    public function getContactRateCountAttribute()
    {
        /*
         *  нужно получить кол-во оценок данным контактом скилов текущего пользователя (контакта)
         */
    }

    public function getUserRateCountAttribute()
    {
        /*
         *  нужно получить кол-во оценов пользователем (контакта) текущего пользователя
         */
    }
}
