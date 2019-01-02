<?php
/**
 * PHP Version 7.2
 *
 * @category Factory
 * @package  Global
 * @author   Thiago Mallon <thiagomallon@gmail.com>
 * @license  MIT https://opensource.org/licenses/MIT
 * @link     https://www.linkedin.com/in/thiago-mallon/
 */

use Faker\Generator as Faker;
use Illuminate\Foundation\Inspiring;
use App\User;

$factory->define(
    App\HomeMessage::class, function (Faker $faker) {
        return [
        'user_id' => User::all()->random()->id,        
        'message' => Inspiring::quote(), // filling with Laravel inspiring quotes =) 
        ];
    }
);
