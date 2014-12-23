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
                'title'       => 'Tag',
                'description' => 'The URL parameter used to search for posts.',
                'default'     => '{{ :tag }}',
                'type'        => 'string'
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