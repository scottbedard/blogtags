<?php namespace Bedard\BlogTags\Models;

use Model;
use RainLab\Blog\Models\Post;

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
        'posts' => [
            'RainLab\Blog\Models\Post',
            'table' => 'bedard_blogtags_post_tag',
            'order' => 'published_at desc'
        ]
    ];

    /**
     * @var array Fillable fields
     */
    public $fillable = ['name'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required|unique:bedard_blogtags_tags|regex:/^[a-z0-9-]+$/'
    ];

    public $customMessages = [
        'name.required' => 'A tag name is required.',
        'name.unique'   => 'A tag by that name already exists.',
        'name.regex'    => 'Tags may only contain alpha-numeric characters and hyphens.'
    ];

    /**
     * Convert tag names to lower case
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = strtolower($value);
    }

}
