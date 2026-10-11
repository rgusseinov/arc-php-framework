<?php

namespace Arc\Framework\Models;

use Arc\Framework\Database\Model;

class Company extends Model {
    protected static string $table = 'companies';

    protected array $fillable = [
        'name',
        'created_at'
    ];
    
    public function employees(){
        return $this->hasMany(Employee::class);
    }
}


