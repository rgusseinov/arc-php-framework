<?php

namespace Arc\Framework\Models;

use Arc\Framework\Database\Model;
use Arc\Framework\Models\User as ModelsUser;

class Post extends Model {
    protected static string $table = 'posts';

    protected array $fillable = [
        'user_id',
        'title',
        'body',
    ];

    public function user(){
        return $this->belongsTo(ModelsUser::class);
    }
}