<?php namespace Bedard\BlogtagsPlugin\Updates;

use October\Rain\Database\Updates\Migration;
use DbDongle;

class UpdateTimestampsNullable extends Migration
{
    public function up()
    {
        DbDongle::disableStrictMode();

        DbDongle::convertTimestamps('bedard_blogtags_tags');
    }

    public function down()
    {
        // ...
    }
}
