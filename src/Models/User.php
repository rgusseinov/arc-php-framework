<?php

class User extends Model
{
    protected static string $table = 'users';

    protected array $fillable = [
        'email',
        'full_name',
        'password_hash',
        'created_at'
    ];
    
    public function posts(){
        return $this->hasMany(Post::class);
    }
}


