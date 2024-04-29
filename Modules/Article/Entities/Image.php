<?php

namespace Modules\Article\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $article_id
 * @property string $title
 * @property string $original_name
 * @property string $image
 * @property string $type
 * @property string $url
 */
class Image extends Model
{
    protected $table = 'images_articles';

    public $timestamps = false;

    protected $fillable = [
        'id',
        'article_id',
        'title',
        'original_name',
        'image',
        'type',
        'url',
    ];

    protected $casts = [
        'id' => 'integer',
        'article_id' => 'integer',
        'title' => 'string',
        'original_name' => 'string',
        'image' => 'string',
        'type' => 'string',
        'url' => 'string',
    ];

    protected $hidden = [];

    public static array $relations_ = [
        'article'
    ];

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }
}
