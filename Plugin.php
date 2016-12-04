<?php namespace Bedard\BlogTags;

use Backend;
use Bedard\BlogTags\Models\Tag;
use Config;
use Event;
use System\Classes\PluginBase;
use RainLab\Blog\Controllers\Posts as PostsController;
use RainLab\Blog\Models\Post as PostModel;

/**
 * BlogTags Plugin Information File
 */
class Plugin extends PluginBase
{
    /**
     * @var array   Require the RainLab.Blog plugin
     */
    public $require = ['RainLab.Blog'];

    /**
     * @var array   Container for tags to be attached
     */
    private $tags = [];

    /**
     * Returns information about this plugin
     *
     * @return  array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Blog Tags Extension',
            'description' => 'Enables tagging blog posts and display related articles.',
            'author'      => 'Scott Bedard',
            'icon'        => 'icon-tags',
            'homepage'    => 'https://github.com/scottbedard/blogtags'
        ];
    }

    /*
     * Owl Registration
     *
     * @return  array
     */
    public function registerFormWidgets()
    {
        return [
            'Owl\FormWidgets\Tagbox\Widget' => [
                'label' => 'Tagbox',
                'code'  => 'owl-tagbox'
            ]
        ];
    }

    /**
     * Register components
     *
     * @return  array
     */
    public function registerComponents()
    {
        return [
            'Bedard\BlogTags\Components\BlogTags'      => 'blogTags',
            'Bedard\BlogTags\Components\BlogTagSearch' => 'blogTagSearch',
            'Bedard\BlogTags\Components\BlogRelated'   => 'blogRelated'
        ];
    }

    public function boot()
    {
        // Extend the navigation
        Event::listen('backend.menu.extendItems', function($manager) {
           $manager->addSideMenuItems('RainLab.Blog', 'blog', [
                'tags' => [
                    'label' => 'Tags',
                    'icon'  => 'icon-tags',
                    'code'  => 'tags',
                    'owner' => 'RainLab.Blog',
                    'url'   => Backend::url('bedard/blogtags/tags')
                ]
            ]);
        });

        // Extend the controller
        PostsController::extendFormFields(function($form, $model, $context) {
            if (!$model instanceof PostModel) return;
            $form->addSecondaryTabFields([
                'tagbox' => [
                    'label'   => 'Tags',
                    'tab'     => 'rainlab.blog::lang.post.tab_categories',
                    'type'    => 'owl-tagbox',
                    'slugify' => Config::get('bedard.blogtags::slugify', true),
                ]
            ]);
        });

        // Extend the model
        PostModel::extend(function($model) {
            // Relationship
            $model->belongsToMany['tags'] = [
                'Bedard\BlogTags\Models\Tag',
                'table' => 'bedard_blogtags_post_tag',
                'order' => 'name'
            ];

            // getTagboxAttribute()
            $model->addDynamicMethod('getTagboxAttribute', function() use ($model) {
                return $model->tags()->lists('name');
            });

            // setTagboxAttribute()
            $model->addDynamicMethod('setTagboxAttribute', function($tags) use ($model) {
                $this->tags = $tags;
            });
        });

        // Attach tags to model
        PostModel::saved(function($model) {
            if ($this->tags) {
                $ids = [];
                foreach ($this->tags as $name) {
                    $create = Tag::firstOrCreate(['name' => $name]);
                    $ids[] = $create->id;
                }

                $model->tags()->sync($ids);
            }
        });
    }
}
