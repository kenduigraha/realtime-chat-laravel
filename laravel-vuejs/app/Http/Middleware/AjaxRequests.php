<?php
/**
 * PHP Version 7.2
 *
 * @category Middleware
 * @package  App\Http\Middleware
 * @author   Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/kenduigraha/
 */

/**
 * File namespace
 *
 * @subpackage Http\Middleware
 */
namespace App\Http\Middleware;

use Closure;

/**
 * Class AjaxRequests
 *
 * @category Middlewares
 * @package  App\Http\Middleware
 * @author   Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/kenduigraha/
 */
class AjaxRequests
{
    /**
     * Verify if the request has the header X-Requested-With = XMLHttpRequest
     *
     * @param \Illuminate\Http\Request $request The Request object
     * @param \Closure                 $next    A Clousure object
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!$request->ajax()) {
            return redirect()->route('login')->withErrors(['msg', 'The Message']);;
        }

        return $next($request);
    }
}
