<?php namespace Bedard\BlogTags\Components;

use Bedard\BlogTags\Models\Tag;
use Cms\Classes\ComponentBase;

class BlogTags extends ComponentBase
{

    /**
     * Component Registration
     * @return array
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
     * @return array
     */
    public function defineProperties()
    {
        return [
            'hideOrphans' => [
                'title'             => 'Orphaned Tags',
                'description'       => 'Tags are orphaned when they no longer have blog posts associated with them.',
                'showExternalParam' => false,
                'default'           => true,
                'type'              => 'dropdown',
                'options' => [
                    1 => 'Hide',
                    0 => 'Show'
                ],
                'default'           => 'Hide'
            ]
        ];
    }

    /**
     * Return the blog tags
     */
    public function tags()
    {
        $tags = Tag::with('posts');
        if ($this->property('hideOrphans')) $tags->has('posts', '>', 0);
        return $tags->get();
    }

}