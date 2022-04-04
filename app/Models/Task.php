<?php

namespace App\Models;

use App\Enums\TaskStatus;
use App\Traits\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, Uuid, SoftDeletes;

    /**
     * Disable auto increment
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * Set Key Type
     *
     * @var string
     */
    protected $keyType = 'uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['title', 'body', 'user_id'];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, mixed>
     */
    protected $casts = [
        'status' => TaskStatus::class,
    ];

    /**
     * Task Owner Relationship
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
