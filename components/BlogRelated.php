<?php namespace Bedard\BlogTags\Components;

use Cms\Classes\Page;
use Cms\Classes\ComponentBase;
use DB;
use RainLab\Blog\Models\Post;

class BlogRelated extends ComponentBase
{
    /**
     * @var Illuminate\Database\Eloquent\Collection | array
     */
    public $posts = [];

    /**
     * Reference to the page name for linking to posts.
     * @var string
     */
    public $postPage;


    /**
     * Component Registration
     *
     * @return  array
     */
    public function componentDetails()
    {
        return [
            'name'        => 'Related Posts',
            'description' => 'Provides related blog posts'
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
            'slug' => [
                'title'             => 'rainlab.blog::lang.settings.post_slug',
                'description'       => 'rainlab.blog::lang.settings.post_slug_description',
                'default'           => '{{ :slug }}',
                'type'              => 'string'
            ],
            'results' => [
                'title'             => 'Results',
                'description'       => 'Number of related posts to display (zero displays all related posts).',
                'type'              => 'string',
                'default'           => '5',
                'validationPattern' => '^[0-9]+$',
                'validationMessage' => 'The results must be a number.',
                'showExternalParam' => false
            ],
            'orderBy' => [
                'title'             => 'Sort by',
                'description'       => 'The value used to sort related posts.',
                'type'              => 'dropdown',
                'options' => [
                    false           => 'Relevance (# of shared tags)',
                    'title'         => 'Title',
                    'published_at'  => 'Published at',
                    'updated_at'    => 'Updated at',
                ],
                'default'           => false,
                'showExternalParam' => false
            ],
            'direction' => [
                'title'             => 'Order',
                'description'       => 'The order to sort related posts in.',
                'type'              => 'dropdown',
                'options' => [
                    'asc'           => 'Ascending',
                    'desc'          => 'Descending',
                ],
                'default'           => 'desc',
                'showExternalParam' => false
            ],
            'postPage' => [
                'title'       => 'Post page',
                'description' => 'Page to show linked posts',
                'type'        => 'dropdown',
                'default'     => 'blog/post',
                'group'       => 'Links',
            ],
        ];
    }

    protected function prepareVars()
    {
        $this->postParam = $this->page['postParam'] = $this->property('postParam');
    }

    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    /**
     * Load post and start building query for related posts
     */
    public function onRun()
    {
        //Prepare vars
        $this->prepareVars();

        // Load the target post
        $post = Post::where('slug', $this->property('slug'))
            ->with('tags')
            ->first();

        // Abort if there is no source, or it has no tags
        if (!$post || (!$tagIds = $post->tags->lists('id')))
            return;

        // Start building our query for related posts
        $query = Post::isPublished()
            ->where('id', '<>', $post->id)
            ->whereHas('tags', function($tag) use ($tagIds) {
                $tag->whereIn('id', $tagIds);
            })
            ->with('tags');

        // Sort the related posts
        $subQuery = DB::raw('(
            select count(*)
            from `bedard_blogtags_post_tag`
            where `bedard_blogtags_post_tag`.`post_id` = `rainlab_blog_posts`.`id`
            and `bedard_blogtags_post_tag`.`tag_id` in ('.implode(', ', $tagIds).')
        )');
        $key = $this->property('orderBy') ?: $subQuery;
        $query->orderBy($key, $this->property('direction'));

        // Limit the number of results
        if ($take = intval($this->property('results')))
            $query->take($take);

        // Execute the query
        $posts = $query->get();

        /*
         * Add a "url" helper attribute for linking to each post
        */
        $posts->each(function($post)
        {
           $post->setUrl($this->postPage,$this->controller);
        });

        $this->posts = $posts;

    }

}
