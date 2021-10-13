<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read Carbon $date
 */
class Row extends Model
{
    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'date' => 'date',
    ];
    protected $fillable = [
        'id',
        'name',
        'date',
    ];
}