<?php

namespace Arc\Framework\Models;

use Arc\Framework\Database\Model;

class Company extends Model {
    protected static string $table = 'companies';

    protected array $fillable = [
        'name',
        'created_at'
    ];
    
    /**
     * @return list<array<string, mixed>>
     */
    public function employees(): array {
        return $this->hasMany(Employee::class);
    }
}


