<?php


namespace App\Tests\DummyTest;

use App\Tests\WebTestCase;

/**
 * Class DummyTest
 * @package App\Tests\DummyTest
 * @author Dennis Langen <dennis.langen@i22.de>
 */
class DummyTest extends WebTestCase
{

    public function testCanLoadFixtures()
    {
        $user = $this->doctrine->getRepository('App:User')->findOneBy(['slug' => 'markus']);
        $this->assertSame('markus', $user->getSlug());
    }
}