<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Validation rules for model
     *
     * @param string $type
     * @return array
     */
    public static function getRules(string $type = ''): array
    {
        match ($type) {
            'update' => $rules = [
                'email' => 'email',
                'lengthBetween' => [
                    ['name', 3, 255],
                    ['email', 5, 255],
                ]
            ],

            default => $rules = [
                'required' => ['title', 'content'],
                'lengthBetween' => [
                    ['title', 1, 255],
                    ['content', 1, 65535],
                ],
                'email' => 'email',
            ],
        };

        return $rules;
    }
}
