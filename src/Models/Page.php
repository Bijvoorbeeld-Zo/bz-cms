<?php

namespace JornBoerema\BzCMS\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Page extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'elements',
    ];

    protected $casts = [
        'elements' => 'array',
    ];

    public function getUrl(): string
    {
        $slug = $this->slug;

        if(!str_starts_with($slug, '/')){
            $slug = '/' . $slug;
        }

        return config('app.url') . $slug;
    }
}
