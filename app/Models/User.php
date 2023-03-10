<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * Get the blog post.
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * @param string $value
     * @return void
     */
    public function setPasswordAttribute(string $value): void
    {
        $this->attributes['password'] = password_hash($value, PASSWORD_DEFAULT);
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
            'signIn' => $rules = [
                'required' => ['email', 'password'],
                'email' => 'email',
            ],

            'update' => $rules = [
                'email' => 'email',
                'equals' => [
                    ['password', 'confirmPassword']
                ],
                'lengthBetween' => [
                    ['name', 3, 255],
                    ['email', 5, 255],
                ]
            ],

            default => $rules = [
                'required' => ['name', 'email', 'name'],
                'equals' => [
                    ['password', 'confirmPassword']
                ],
                'email' => 'email',
            ],
        };

        return $rules;
    }

    /**
     * Find user by email
     *
     * @param string $email
     * @return Model|null
     */
    public static function getUserByEmail(string $email): Model|null
    {
        return self::query()->where('email', $email)->first();
    }
}
