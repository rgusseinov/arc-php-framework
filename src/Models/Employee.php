<?php

namespace Arc\Framework\Models;

use Arc\Framework\Database\Model;

class Employee extends Model {
    protected static string $table = 'employees';

    protected array $fillable = [
        'company_id',
        'full_name',
        'position',
        'salary',
        'created_at'
    ];

    public function company(){
        return $this->belongsTo(Company::class);
    }
}


