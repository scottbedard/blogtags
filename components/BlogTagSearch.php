<?php namespace Bedard\BlogTags\Components;

use Bedard\BlogTags\Models\Tag;
use Cms\Classes\ComponentBase;

class BlogTagSearch extends ComponentBase
{

    public $tag;

    /**
     * Component Registration
     * @return array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Tag Search',
            'description' => 'Provides a list of posts with a certain tag.'
        ];
    }

    /**
     * Component Properties
     * @return array
     */
    public function defineProperties()
    {
        return [
            'tag' => [
                'title'         => 'Tag',
                'description'   => 'The URL parameter used to search for posts.',
                'default'       => '{{ :tag }}',
                'type'          => 'string'
            ],
            'page' => [
                'title'         => 'Page',
                'description'   => 'The URL parameter defining the page of results.',
                'default'       => '{{ :page }}',
                'type'          => 'string'
            ],
            'resultsPerPage' => [
                'title'         => 'Results per page',
                'description'   => 'The number of posts to display per page. Set to zero to display all results.',
                'default'       => '10',
                'type'          => 'string',
                'validationPattern' => '^(0+)?[1-9]\d*$',
                'validationMessage' => 'Results per page must be a whole number greater than or equal to zero.'
            ]
        ];
    }

    /**
     * Query the tag and posts belonging to it
     */
    public function onRun()
    {
        $this->tag = Tag::where('name', $this->property('tag'))
            ->with('posts')
            ->first();
    }

}