<?php
/**
 * PHP Version 7.2
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/kenduigraha/
 */

/**
 * File namespace
 *
 * @subpackage Http\Controllers
 */
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\HomeMessageRequest; 
use App\Events\PublicMessageSent;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

/**
 * Class DashboardController
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/kenduigraha/
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
     * @param Illuminate\Http\Request; $request The request data
     *
     * @method string sendPublicMessage Method dispatch the broadcasting event
     * @return string 
     */
    public function sendPublicMessage(Request $request): object
    {
        $payload = $request->all();
        $currentUser = auth()->user();
        
        // there's file
        if (!empty($payload['file'])) {
            $data = $request->files->get('file');
            $fileName = auth()->user()->email . '-' . microtime() . '.' . $data->guessExtension();
            // dd($fileName);
            Storage::disk('local')->putFileAs('public/images', new File($data), $fileName);
            
            $urlImage = asset(Storage::url('images/' . $fileName));

            $text = empty($payload['text']) ? '' : $payload['text'];
            
            $created = auth()->user()->getMessages()->create(['text' => $text])
                ->files()->create(['title' => $fileName, 'file' => $urlImage]);
            
            $data = $message = \App\HomeMessage::with('files')->where('id', $created->message_id)->first();
            $data->files->file = Storage::download('public/images/' . $fileName, 'test', array(
                'Content-Type'=>'application/pdf',
                'Content-disposition'=>'attachment'
          ));
            // dd($data);

        } else {
            $created = auth()->user()->getMessages()
            ->create(['text' => $payload['text']]); // save the message

            $data = $message = \App\HomeMessage::with('files')->where('id', $created->id)->first();
        }
        
        // dispatch the event 
        PublicMessageSent::dispatch(json_decode($data), $currentUser);

        return response(request('message'), 200)->header('Content-disposition', 'attachment; filename=' . $fileName)->header('Content-Type', 'application/pdf'); // return in json format
    }
}
