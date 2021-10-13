<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\RowRecordCreated;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string $file_id
 * @property-read Carbon $date
 */
class Row extends Model
{
    use HasFactory;

    public $incrementing = false;
    public $timestamps = false;

    protected $casts = [
        'date' => 'date',
    ];
    protected $fillable = [
        'id',
        'file_id',
        'name',
        'date',
    ];

    protected $dispatchesEvents = [
        'created' => RowRecordCreated::class,
    ];
}