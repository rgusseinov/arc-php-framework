<?php

namespace Arc\Framework\Models;

use Arc\Framework\Database\Model;

class User extends Model {
    protected static string $table = 'users';

    /** @var list<string> */
    protected array $fillable = [
        'email',
        'full_name',
        'password_hash',
        'created_at'
    ];

    /**
     * @return list<array<string, mixed>>
     */
    public function posts(): array {
        return $this->hasMany(Post::class);
    }
}