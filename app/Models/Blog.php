<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'featured_image',
        'category',
        'tags',
        'is_published'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_published' => 'boolean',
        'tags' => 'array'
    ];

    /**
     * Get the tags as an array.
     *
     * @param  string  $value
     * @return array
     */
    public function getTagsAttribute($value)
    {
        return $value ? explode(',', $value) : [];
    }

    /**
     * Set the tags as a comma-separated string.
     *
     * @param  array  $value
     * @return void
     */
    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = is_array($value) ? implode(',', $value) : $value;
    }
}
