<?php
/**
 * PHP Version 7.2
 *
 * @category Migration
 * @package  Global
 * @author   Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/kenduigraha/
 */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

/**
 * Class CreateHomeMessagesTable
 *
 * @category Migration
 * @package  Global
 * @author   Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/kenduigraha/
 */
class CreateHomeMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'home_messages', function (Blueprint $table) {
                $table->increments('id');
                $table->text('text');
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('home_messages');
    }
}
