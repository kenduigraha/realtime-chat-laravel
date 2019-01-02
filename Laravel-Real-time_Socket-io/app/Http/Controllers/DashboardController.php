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
use App\Http\Requests\HomeMessageRequest; 
use App\Events\PublicMessageSent;   

/**
 * Class DashboardController
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   Thiago Mallon <thiagomallon@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/thiago-mallon/
 */
class DashboardController extends Controller
{
    /**
     * Public method __construct - The constructor method
     *
     * @method void __construct The constructor method
     * @return void 
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Public method index - The index action
     *
     * @method object index The index action
     * @return object Returns a view object
     */
    public function index(): object
    {
        return view('dashboard.home');
    }
    
    /**
     * Public method sendPublicMessage - Method dispatch the broadcasting event
     *
     * @param \App\Http\Requests\HomeMessageRequest $request The request data
     *
     * @method string sendPublicMessage Method dispatch the broadcasting event
     * @return string 
     */
    public function sendPublicMessage(HomeMessageRequest $request): object
    {
        auth()->user()->homeMessages()
            ->create(['message' => $request->message]); // save the message
            // dispatch the event 
            PublicMessageSent::dispatch($request->message);
            return response(request('message'), 200); // return in json format
    }
}
