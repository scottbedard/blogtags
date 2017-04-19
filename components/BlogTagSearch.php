<?php namespace Bedard\BlogTags\Components;

use Cms\Classes\Page;
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
     * @var Illuminate\Database\Eloquent\Collection | array
     */
    public $posts = [];

    /**
     * @var integer             The total number of posts with the tag
     */
    public $totalPosts;

    /**
     * @var integer             The number of posts on the current page
     */
    public $postsOnPage;

    /**
     * @var integer             The current page
     */
    public $currentPage;

    /**
     * @var integer             The number of results per page
     */
    public $resultsPerPage;

    /**
     * @var boolean / integer   The previous page, or false for first page
     */
    public $previousPage;

    /**
     * @var boolean / integer   The next page, or false for last page
     */
    public $nextPage;

    /**
     * @var integer             The last page
     */
    public $lastPage;

     /**
     * Reference to the page name for linking to posts.
     * @var string
     */
    public $postPage;

    /**
     * @var string Reference to the page name for linking to categories.
     */
    public $categoryPage;


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
                'title'         => 'Paginate results',
                'description'   => 'Determines if the results are paginated or not.',
                'type'          => 'checkbox',
                'showExternalParam' => false
            ],
            'page' => [
                'title'         => 'Page',
                'description'   => 'The URL parameter defining the page number.',
                'default'       => '{{ :page }}',
                'type'          => 'string'
            ],
            'resultsPerPage' => [
                'title'         => 'Results',
                'description'   => 'The number of posts to display per page.',
                'default'       => 10,
                'type'          => 'string',
                'validationPattern' => '^(0+)?[1-9]\d*$',
                'validationMessage' => 'Results per page must be a positive whole number.'
            ],
            'postPage' => [
                'title'       => 'Post page',
                'description' => 'Page to show linked posts',
                'type'        => 'dropdown',
                'default'     => 'blog/post',
                'group'       => 'Links',
            ],
            'categoryPage' => [
                'title'       => 'rainlab.blog::lang.settings.posts_category',
                'description' => 'rainlab.blog::lang.settings.posts_category_description',
                'type'        => 'dropdown',
                'default'     => 'blog/category',
                'group'       => 'Links',
            ]
        ];
    }

    /**
     * Query the tag and posts belonging to it
     */
    public function onRun()
    {
        $this->postPage = $this->page['postPage'] = $this->property('postPage');
        $this->categoryPage = $this->page['categoryPage'] = $this->property('categoryPage');

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
            ->orWhere('slug', $this->property('tag'))
            ->with(['posts' => function($posts) {
                $posts->skip($this->resultsPerPage * ($this->currentPage - 1))
                      ->take($this->resultsPerPage);
            }])
            ->first();

        // Store the posts in a better container
        if(empty($this->tag)) {
            $this->posts = null;
            $this->postsOnPage = 0;
        } else {
            $this->posts = $this->tag->posts;
            $this->postsOnPage = count($this->posts);

            // Add a "url" helper attribute for linking to each post
            $this->posts->each(function($post) {
                $post->setUrl($this->postPage,$this->controller);

                if($post->categories->count()) {
                    $post->categories->each(function($category){
                        $category->setUrl($this->categoryPage, $this->controller);
                    });
                }
            });
        }
    }


    public function getPostPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
    }

    public function getCategoryPageOptions()
    {
        return Page::sortBy('baseFileName')->lists('baseFileName', 'baseFileName');
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
            : max(1, $this->totalPosts);

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
