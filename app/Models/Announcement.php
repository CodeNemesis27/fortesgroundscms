<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'content',
        'background_color',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'is_featured' => 'boolean'
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Announcement $announcement) {
            if ($announcement->is_featured) {
                static::where('id', '!=', $announcement->id)
                    ->where('is_featured', true)
                    ->update(['is_featured' => false]);
            }
        });
    }

    public static function getFeatured(): ?self
    {
        return static::where('is_featured', true)->latest()->first();
    }
}
