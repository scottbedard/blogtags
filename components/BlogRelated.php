<?php namespace Bedard\BlogTags\Components;

use Cms\Classes\ComponentBase;
use RainLab\Blog\Models\Post;

class BlogRelated extends ComponentBase
{

    private $query;
    private $tagIds;

    /**
     * Component Registration
     * @return array
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
     * @return array
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
                'description'       => 'The number of results to return.',
                'type'              => 'string',
                'default'           => '6',
                'validationPattern' => '^[0-9]*$',
                'validationMessage' => 'The results must be a whole number greater than zero.',
                'showExternalParam' => false
            ],
            'method' => [
                'title'             => 'Method',
                'description'       => 'The method used to search for related posts.',
                'type'              => 'dropdown',
                'options' => [
                    'newestRelated' => 'Newest Related',
                    'mostRelated'   => 'Most Related'
                ],
                'default'           => 'newestRelated',
                'showExternalParam' => false
            ]
        ];
    }

    /**
     * Load post and start building query for related posts
     */
    public function onRun()
    {
        $post = Post::where('slug', $this->property('slug'))
            ->with('tags')
            ->first();

        if (!$post) return;

        $this->tagIds = [];
        foreach ($post->tags as $tag) $this->tagIds[] = $tag->id;

        if (empty($this->tagIds)) return;

        $this->query = Post::isPublished()
            ->where('id', '<>', $post->id)
            ->whereHas('tags', function($query) {
                $query->whereIn('id', $this->tagIds);
            })
            ->with('tags');
    }

    /**
     * Returns related posts
     */
    public function posts()
    {
        if (!$this->query) return [];
        return $this->property('method') == 'mostRelated'
            ? $this->mostRelated()
            : $this->newestRelated();
    }

    /**
     * Queries related posts
     */
    private function newestRelated()
    {
        return $this->query
            ->take($this->property('results'))
            ->get();
    }

    /**
     * Queries related posts, and sorts them by relevance
     */
    private function mostRelated()
    {
        $results = $matches = [];
        $related = $this->query->get();

        foreach ($related as $post) {
            $overlap = 0;
            foreach ($post->tags as $tag)
                if (in_array($tag->id, $this->tagIds)) $overlap++;
            $matches[$post->id] = $overlap;
        }
        arsort($matches);

        foreach ($matches as $id => $overlap) {
            if (count($results) >= $this->property('results')) break;
            foreach ($related as $post) {
                if ($post->id == $id) $results[] = $post;
                continue;
            }
        }

        return $results;
    }

}