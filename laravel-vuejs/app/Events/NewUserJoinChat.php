<?php
/**
 * PHP Version 7.2
 *
 * @category Events
 * @package  App\Events
 * @author   Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/kenduigraha/
 */

/**
 * File namespace
 *
 * @subpackage Events
 */
namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * Class PublicMessageSent
 *
 * @category Events
 * @package  App\Events
 * @author   Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/kenduigraha/
 */
class NewUserJoinChat implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

     /**
      * Public property $message - The message to be publicly spread
      *
      * @var string $message The message to be publicly spread
      */
    public $user;

    /**
     * Create a new event instance.
     *
     * @param string $message Receive the message and add to the payload
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn(): object
    {
        return new Channel('public-new-user-join');
    }
}
