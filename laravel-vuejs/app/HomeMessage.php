<?php
/**
 * PHP Version 7.2
 *
 * @category Model
 * @package  App
 * @author   Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/kenduigraha/
 */

/**
 * File namespace
 */
namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class HomeMessage
 *
 * @category Model
 * @package  App
 * @author   Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/kenduigraha/
 */
class HomeMessage extends Model
{
     /**
      * Protected property $guarded - Attributes that aren't mass assignable
      *
      * @var array $guarded Attributes that aren't mass assignable
      */
    protected $guarded = [];

    /**
     * Public method user - User home message
     *
     * @method object user User home message
     * @return object 
     */
    public function user(): object
    {
        return $this->belongsTo('App\User');
    }

    public function files()
    {
        return $this->hasOne('App\Files', 'message_id');
    }
}
