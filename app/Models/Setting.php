<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'key'];

    /**
     * Run Action When Boot the model
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();

        // When Creating New Model
        static::creating(function () {
            is_null(self::first()) ?: throw new Exception('Can\'t Create New Setting It is Exist.');
        });
    }
}
