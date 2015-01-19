<?php namespace Bedard\BlogTags\Widgets;

use Backend\Classes\FormField;
use Backend\Classes\FormWidgetBase;
use Bedard\BlogTags\Models\Tag;
use RainLab\Blog\Models\Post;

class Tagbox extends FormWidgetBase
{
    /**
     * Load tagbox styles and scripts
     */
    public function loadAssets()
    {
        $this->addCss('/plugins/bedard/blogtags/widgets/tagbox/assets/css/tagbox.css');
        $this->addJs('/plugins/bedard/blogtags/widgets/tagbox/assets/js/jquery-ui.custom.min.js');
        $this->addJs('/plugins/bedard/blogtags/widgets/tagbox/assets/js/tagbox.js');
    }

    /**
     * Load the blog post's existing tags
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();

        $tags = [];
        if ($this->model->id)
            foreach ($this->model->tags as $tag) $tags[] = $tag->name;
        
        $this->vars['tags'] = implode(',', $tags);
    }

    /**
     * Render the form widget
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('tagbox');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        return FormField::NO_SAVE_DATA;
    }

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        // Return if we have no post data
        if (empty(post('Post')['tags']) || ! $tags = post('Post')['tags'])
            return;

        // Remove old tags if needed
        if ($this->model->id) $tags = $this->removeTags($tags);

        // Attach new tags
        $this->attachTags($tags);
    }

    /**
     * Removes tags that have been deleted, and removes unchanged tags from array
     * @param   array
     * @return  array
     */
    private function removeTags($postTags)
    {
        $newTags = $unchangedTags = [];

        // Detach tags that are being deleted, and keep track of unchanged tags
        foreach ($this->model->tags as $tag) {
            // If the tag isn't in the post array, remove it
            if (!in_array($tag->name, $postTags)) $this->model->tags()->detach($tag->id);

            // Keep track of existing tags
            else $unchangedTags[] = $tag->name;
        }

        // Determine which tags are new and return them
        foreach ($postTags as $tag)
            if (!in_array($tag, $unchangedTags)) $newTags[] = $tag;

        return $newTags;
    }

    /**
     * Attach new tags to a blog post
     */
    private function attachTags($newTags)
    {
        // The post we're adding tags to
        $post = $this->model->id ? $this->model : new Post;

        // firstOrCreate and attach new tags
        foreach ($newTags as $tag) {
            $tag = Tag::firstOrCreate(['name' => $tag]);
            $post->tags()->add($tag, $this->sessionKey);
        }
    }
}