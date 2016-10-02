<?php namespace Bedard\BlogtagsPlugin\Updates;

use October\Rain\Database\Updates\Migration;
use Schema;
use System\Classes\PluginManager;
use Illuminate\Support\Facades\DB;

class CreateTagsTable extends Migration
{

    private $dbType;

    public function up()
    {
        if(PluginManager::instance()->hasPlugin('RainLab.Blog'))
        {
            $this->dbType = DB::connection()->getPdo()->getAttribute(\PDO::ATTR_DRIVER_NAME);

            Schema::create('bedard_blogtags_tags', function($table)
            {
                $this->dbSpecificSetup($table);

                $table->increments('id');
                $table->string('name')->unique()->nullable();
                $table->timestamps();
            });

            Schema::create('bedard_blogtags_post_tag', function($table)
            {
                $this->dbSpecificSetup($table);

                $table->integer('tag_id')->unsigned()->nullable()->default(null);
                $table->integer('post_id')->unsigned()->nullable()->default(null);
                $table->index(['tag_id', 'post_id']);
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


    /**
     * @param $table
     */
    private function dbSpecificSetup(&$table)
    {
        switch ($this->dbType) {
            case 'pgsql':
                break;
            case 'mysql':
                //@todo SET sql_mode='ANSI_QUOTES';
                $table->engine = 'InnoDB';
                break;
        }
    }


}
