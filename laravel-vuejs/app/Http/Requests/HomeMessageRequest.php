<?php
/**
 * PHP Version 7.2
 *
 * @category Requests
 * @package App\Http\Requests
 * @author  Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license MIT https://opensource.org/licenses/MIT
 * @link    https://www.linkedin.com/in/kenduigraha/
 */

/**
 * File namespace
 *
 * @subpackage Http\Requests
 */
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class HomeMessageRequest
 *
 * @category Requests
 * @package App\Http\Requests
 * @author  Ken Duigraha Putra <kenduigraha@gmail.com>
 * @license MIT https://opensource.org/licenses/MIT
 * @link    https://www.linkedin.com/in/kenduigraha/
 */
class HomeMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'message' => 'required|string|min:1|max:200',
        ];
    }
}
