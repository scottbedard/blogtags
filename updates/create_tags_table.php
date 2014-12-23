<?php namespace Bedard\BlogtagsPlugin\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateTagsTable extends Migration
{

    public function up()
    {
        Schema::create('bedard_blogtags_tags', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
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

    public function down()
    {
        Schema::dropIfExists('bedard_blogtags_tags');
        Schema::dropIfExists('bedard_blogtags_post_tag');
    }

}
