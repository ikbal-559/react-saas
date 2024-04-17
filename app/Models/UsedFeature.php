<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsedFeature extends Model
{
    use HasFactory;

    protected $fillable = [
        'feature_id',
        'user_id',
        'credits',
        'data'
    ];

    protected function casts(): array
    {
        return [
            'data' => 'array'
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function feature()
    {
        return $this->belongsTo(Feature::class, 'feature_id');
    }

}
