<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Reward
 * @package App\Models
 * @property int $id
 * @property string $name
 * @property string $slug
 * @property float $points
 */
class Reward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'points'
    ];

}
