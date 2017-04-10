<?php namespace Bedard\BlogTags\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;
use Bedard\BlogTags\Models\Tag;

class AddTagSlug extends Migration
{
    public function up()
    {
        if(!PluginManager::instance()->hasPlugin('RainLab.Blog'))
        {
            return;
        }

        Schema::table('bedard_blogtags_tags', function($table)
        {
            $table->string('slug', 255);
        });

        $this->fillSlugs();

        Schema::table('bedard_blogtags_tags', function($table)
        {
            $table->unique('slug');
        });
    }

    public function down()
    {
        if(!PluginManager::instance()->hasPlugin('RainLab.Blog'))
        {
            return;
        }

        Schema::table('bedard_blogtags_tags', function($table)
        {
            $table->dropColumn('slug');
        });
    }

    private function fillSlugs()
    {
        $tags = Tag::all();

        foreach ($tags as $tag)
        {
            $tag->slug = str_slug($tag->name);
            $tag->save();
        }
    }
}
