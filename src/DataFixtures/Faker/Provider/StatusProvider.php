<?php


namespace App\DataFixtures\Faker\Provider;

use Faker\Provider\Base;

/**
 * Class StatusProvider
 * @package App\DataFixtures\Faker\Provider
 * @author Dennis Langen <dennis.langen@i22.de>
 */
class StatusProvider extends Base
{
    public static function status()
    {
        $status = [
            'active',
            'inactive',
            'closed',
            'rejected'
        ];

        return $status[array_rand($status)];
    }
}