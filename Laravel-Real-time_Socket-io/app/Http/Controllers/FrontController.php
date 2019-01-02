<?php
/**
 * PHP Version 7.2
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   Thiago Mallon <thiagomallon@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/thiago-mallon/
 */

/**
 * File namespace
 *
 * @subpackage Http\Controllers
 */
namespace App\Http\Controllers;

use Illuminate\Http\Request;        
use Illuminate\Foundation\Inspiring; 
use App\Events\NewUserJoinChat;

/**
 * Class FrontController
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   Thiago Mallon <thiagomallon@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/thiago-mallon/
 */ 
class FrontController extends Controller
{
    /**
     * Public method __construct - Constructor method
     *
     * @method void __construct Constructor method
     * @return void 
     */
    public function __construct()
    {
        //$this->middleware('guest');
    }

    /**
     * Public method index - Index action
     *
     * @method object index Index action
     * @return object Returns the view object
     */
    public function index(): object
    {
        /* The only way I found to send only the message to vue */
        $homeMessage = \App\HomeMessage::all();
        // dd($homeMessage);
        $message = ($homeMessage)? 
            $homeMessage : Inspiring::quote();
        // $message = json_encode(['message'=> $message]);
        // dd($message);
        return view('front.home', compact('message'));
    }

    public function getAllMessages(): object
    {
        /* The only way I found to send only the message to vue */
        $homeMessage = \App\HomeMessage::all();
        // dd($homeMessage);
        $message = ($homeMessage)? 
            $homeMessage : Inspiring::quote();
        return response()->json($message);
    }

    public function doNewUserJoinChat(): object
    {
        $currentUser = auth()->user();

        NewUserJoinChat::dispatch($currentUser->username);
        return response(request('message'), 200); // return in json format
    }
}
