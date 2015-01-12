<?php namespace Bedard\BlogTags\Components;

use Bedard\BlogTags\Models\Tag;
use Cms\Classes\ComponentBase;
use Rainlab\Blog\Models\Post;

class BlogTagSearch extends ComponentBase
{

    /**
     * @var Bedard\BlogTags\Models\Tag
     */
    public $tag;

    /**
     * @var integer                 The total number of posts with the tag
     */
    public $totalPosts;

    /**
     * @var integer                 The number of posts on the current page
     */
    public $postsOnPage;

    /**
     * @var integer                 The current page
     */
    public $currentPage;

    /**
     * @var integer                 The number of results per page
     */
    public $resultsPerPage;

    /**
     * @var boolean / integer       The previous page, or false for first page
     */
    public $previousPage;

    /**
     * @var boolean / integer       The next page, or false for last page
     */
    public $nextPage;

    /**
     * @var integer                 The last page
     */
    public $lastPage;
    

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
            'pagination' => [
                'title'         => 'Pagination',
                'description'   => 'Determines if the results are paginated or not.',
                'default'       => 0,
                'type'          => 'dropdown',
                'options' => [
                    1 => 'On',
                    0 => 'Off'
                ],
                'showExternalParam' => false
            ],
            'page' => [
                'title'         => 'Page',
                'description'   => 'The URL parameter defining the page of results.',
                'default'       => '{{ :page }}',
                'type'          => 'string'
            ],
            'resultsPerPage' => [
                'title'         => 'Number of results to display per page.',
                'description'   => 'The number of posts to display per page.',
                'default'       => '10',
                'type'          => 'string',
                'validationPattern' => '^(0+)?[1-9]\d*$',
                'validationMessage' => 'Results per page must be a positive whole number.'
            ]
        ];
    }

    /**
     * Query the tag and posts belonging to it
     */
    public function onRun()
    {
        $this->onLoadPage($this->property('page'));
    }

    /**
     * Load a page of posts
     */
    public function onLoadPage($page = false)
    {
        // Determine which page we're attempting to load
        $this->currentPage = $page ?: intval(post('page'));

        // Calculate the pagination variables
        $this->calculatePagination();

        // Query the tag with it's posts
        $this->tag = Tag::where('name', $this->property('tag'))
            ->with(['posts' => function($posts) {
                $posts->skip($this->resultsPerPage * ($this->currentPage - 1))
                      ->take($this->resultsPerPage);
            }])
            ->first();

        // Count the posts being returned
        $this->postsOnPage = $this->tag
            ? count($this->tag->posts)
            : 0;
    }

    /**
     * Calculate the pagination variables
     */
    private function calculatePagination()
    {
        // Count the number of posts with this tag
        $this->totalPosts = Post::whereHas('tags', function($tag) {
                $tag->where('name', $this->property('tag'));
            })
            ->count();

        // Calculate the results per page
        $this->resultsPerPage = $this->property('pagination')
            ? intval($this->property('resultsPerPage'))
            : $this->totalPosts;

        // Calculate the last page
        $this->lastPage = ceil($this->totalPosts / $this->resultsPerPage);

        // Prevent the current page from being one that doesn't exist
        if ($this->currentPage < 1) $this->currentPage = 1;
        if ($this->currentPage > $this->lastPage) $this->currentPage = $this->lastPage;

        // Calculate the previous page
        $this->previousPage = $this->currentPage > 1
            ? $this->currentPage - 1
            : false;

        // Calculate the next page
        $this->nextPage = $this->currentPage < $this->lastPage
            ? $this->currentPage + 1
            : false;
    }
}