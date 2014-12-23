<?php namespace Bedard\BlogTags\Models;

use Model;

/**
 * Tag Model
 */
class Tag extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_blogtags_tags';

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'posts' => ['RainLab\Blog\Models\Post', 'table' => 'rainlab_blog_posts', 'order' => 'title']
    ];

}