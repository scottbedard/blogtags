<?php namespace Bedard\BlogTags\Controllers;

use BackendMenu;
use Backend\Classes\Controller;
use Bedard\BlogTags\Models\Tag;
use Flash;

/**
 * Tags Back-end Controller
 */
class Tags extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('RainLab.Blog', 'blog', 'tags');
    }

    /**
     * Delete tags
     *
     * @return  $this->listRefresh()
     */
    public function index_onDelete()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds))
            $delete = Tag::whereIn('id', $checkedIds)->delete();

        if (!isset($delete) && !$delete)
            return Flash::error('An unknown error has occured.');

        Flash::success('Successfully deleted tags.');

        return $this->listRefresh();
    }

    /**
     * Removes tags with no associated posts
     *
     * @return  $this->listRefresh()
     */
    public function index_onRemoveOrphanedTags()
    {
        if (!$delete = Tag::has('posts', 0)->delete())
            return Flash::error('An unknown error has occured.');

        Flash::success('Successfully deleted orphaned tags.');

        return $this->listRefresh();
    }
}
