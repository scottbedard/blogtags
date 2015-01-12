<?php namespace Bedard\BlogTags;

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
     * Returns information about this plugin.
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Blog Tags Extension',
            'description' => 'Enables tagging of blog posts.',
            'author'      => 'Scott Bedard',
            'icon'        => 'icon-tags'
        ];
    }

    /*
     * Register the tagbox form widget
     */
    public function registerFormWidgets()
    {
        return [
            'Bedard\BlogTags\Widgets\Tagbox' => [
                'label' => 'Tagbox',
                'alias' => 'tagbox'
            ]
        ];
    }

    /**
     * Register components
     * @return array
     */
    public function registerComponents()
    {
        return [
            'Bedard\BlogTags\Components\BlogTags'       => 'blogTags',
            'Bedard\BlogTags\Components\BlogTagSearch'  => 'blogTagSearch',
            'Bedard\BlogTags\Components\BlogRelated'    => 'blogRelated',
        ];
    }

    /**
     * Add tags field to blog posts
     */
    public function boot()
    {
        // Extend the posts model to establish the new tags relationship
        PostModel::extend(function ($model) {
            $model->belongsToMany['tags'] = ['Bedard\BlogTags\Models\Tag', 'table' => 'bedard_blogtags_post_tag', 'order' => 'name'];
        });

        // Extend the controller and add the tags field
        PostsController::extendFormFields(function($form, $model, $context) {
            if (!$model instanceof PostModel) return;
            $form->addSecondaryTabFields([
                'tags' => [
                    'label' => 'Tags',
                    'tab'   => 'rainlab.blog::lang.post.tab_categories',
                    'type'  => 'tagbox'
                ]
            ]);
        });
    }
}
