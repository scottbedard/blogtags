<?php namespace Bedard\BlogTags\Components;

use Bedard\BlogTags\Models\Tag;
use Cms\Classes\ComponentBase;
use DB;

class BlogTags extends ComponentBase
{
    /**
     * @var Illuminate\Database\Eloquent\Collection | array
     */
    public $tags = [];

    /**
     * Component Registration
     *
     * @return  array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Tags List',
            'description' => 'Displays a list of tags.'
        ];
    }

    /**
     * Component Properties
     *
     * @return  array
     */
    public function defineProperties()
    {
        return [
            'hideOrphans' => [
                'title'             => 'Hide orphaned tags',
                'description'       => 'Hides tags with no associated posts.',
                'showExternalParam' => false,
                'type'              => 'checkbox',
            ],
            'results' => [
                'title'             => 'Results',
                'description'       => 'Number of tags to display (zero displays all tags).',
                'type'              => 'string',
                'default'           => '5',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The results must be a number.',
                'showExternalParam' => false
            ],
            'orderBy' => [
                'title'             => 'Sort by',
                'description'       => 'The value used to sort tags.',
                'type'              => 'dropdown',
                'options' => [
                    false           => 'Posts',
                    'name'          => 'Name',
                    'created_at'    => 'Created at'
                ],
                'default'           => false,
                'showExternalParam' => false
            ],
            'direction' => [
                'title'             => 'Order',
                'description'       => 'The order to sort tags in.',
                'type'              => 'dropdown',
                'options' => [
                    'asc'           => 'Ascending',
                    'desc'          => 'Descending',
                ],
                'default'           => 'desc',
                'showExternalParam' => false
            ]
        ];
    }

    /**
     * Query and return blog posts
     *
     * @return  Illuminate\Database\Eloquent\Collection
     */
    public function onRun()
    {
        // Start building the tags query
        $query = Tag::with('posts');

        // Hide orphans
        if ($this->property('hideOrphans'))
            $query->has('posts', '>', 0);

        // Sort the tags
        $subQuery = DB::raw('(
            select count(*)
            from bedard_blogtags_post_tag
            where bedard_blogtags_post_tag.tag_id = bedard_blogtags_tags.id
        )');
        $key = $this->property('orderBy') ?: $subQuery;
        $query->orderBy($key, $this->property('direction'));

        // Limit the number of results
        if ($take = intval($this->property('results')))
            $query->take($take);

        $this->tags = $query->get();
    }
}
