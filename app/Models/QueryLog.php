<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class QueryLog extends Model
{
    use HasFactory;

    protected $table = 'query_logs';

    protected $fillable = [
        'category',
        'query',
        'created_at',
        'duration',
    ];

    // Disable timestamps since we're manually managing `created_at`
    public $timestamps = false;

    protected $casts = [
        'created_at' => 'datetime',
    ];
}
