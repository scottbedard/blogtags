<?php namespace Bedard\BlogtagsPlugin\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;
use System\Classes\PluginManager;

class CreateTagsTable extends Migration
{

    public function up()
    {
        if(PluginManager::instance()->hasPlugin('RainLab.Blog'))
        {
            Schema::create('bedard_blogtags_tags', function($table)
            {
                $table->engine = 'InnoDB';
                $table->increments('id');
                $table->string('name')->unique()->nullable();
                $table->timestamps();
            });

            Schema::create('bedard_blogtags_post_tag', function($table)
            {
                $table->engine = 'InnoDB';
                $table->integer('tag_id')->unsigned();
                $table->integer('post_id')->unsigned();
                $table->primary(['tag_id', 'post_id']);
                $table->foreign('tag_id')->references('id')->on('bedard_blogtags_tags')->onDelete('cascade');
                $table->foreign('post_id')->references('id')->on('rainlab_blog_posts')->onDelete('cascade');
            });
        }
    }

    public function down()
    {
        if(PluginManager::instance()->hasPlugin('RainLab.Blog'))
        {
            Schema::dropIfExists('bedard_blogtags_post_tag');
            Schema::dropIfExists('bedard_blogtags_tags');
        }
    }

}
