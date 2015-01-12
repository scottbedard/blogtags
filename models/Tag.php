<?php namespace Bedard\BlogTags\Models;

use Model;

/**
 * Tag Model
 */
class Tag extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_blogtags_tags';

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'posts' => ['RainLab\Blog\Models\Post', 'table' => 'bedard_blogtags_post_tag', 'order' => 'published_at desc', 'scope' => 'isPublished']
    ];

    /**
     * @var array Fillable fields
     */
    public $fillable = ['name'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|regex:/^[a-z0-9-]+$/'
    ];

    /**
     * @var array Validation error messages
     */
    public $customMessages = [
        'name.regex' => 'Tags may contain only alpha-numeric characters and hyphens.'
    ];

    /**
     * Convert tag names to lower case
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

}