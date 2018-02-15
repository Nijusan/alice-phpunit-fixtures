<?php

namespace App\Tests;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\BrowserKit\Cookie;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class WebTestCase
 * @package App\Tests
 */
abstract class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase
{

    /** @var Client */
    protected $client;

    /** @var Registry */
    protected $doctrine;

    /** @var array */
    protected $objects;

    /**
     * @throws \ReflectionException
     */
    public function setUp()
    {
        $this->client = static::createClient(['debug' => false]);
        $this->doctrine = $this->client->getContainer()->get('doctrine');
        $this->doctrine->getManager()->getConnection()->getConfiguration()->setSQLLogger(null);

        $this->objects = $this->loadFixtures();
        $this->doctrine->getManager()->clear();
    }

    /**  */
    public function tearDown()
    {
        $this->resetDatabase();
        $this->doctrine->getManager()->clear();
        unset($this->client, $this->doctrine, $this->objects);
        parent::tearDown();
    }

    /**  */
    private function resetDatabase()
    {
        $purger = new ORMPurger($this->doctrine->getManager());
        $this->doctrine->getConnection()->executeUpdate("SET foreign_key_checks = 0;");
        $purger->setPurgeMode(ORMPurger::PURGE_MODE_TRUNCATE);
        $purger->purge();
        $this->doctrine->getConnection()->executeUpdate("SET foreign_key_checks = 1;");
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    private function loadFixtures()
    {
        $fixtureLoader = $this->client->getContainer()->get('fidry_alice_data_fixtures.loader.doctrine');
        return $fixtureLoader->load($this->getFixtureFilePaths());
    }

    /**
     * Fixtures in the fixtures folder within the test folder will be loaded automatically as long
     * as they are provided in .yml format.
     *
     * @return array
     * @throws \ReflectionException
     */
    protected function getFixtureFilePaths()
    {
        $files = [];
        $dir = $this->getFixtureFileDir();
        if (is_dir($dir)) {
            $realFiles = array_diff(scandir($dir), ['.', '..']);
            foreach ($realFiles as $file) {
                $fileEnding = explode('.', $file);
                if (preg_match('/^ya?ml$/', end($fileEnding))) {
                    $files[] = $dir . DIRECTORY_SEPARATOR . $file;
                }
            }
        }
        return $files;
    }

    /**
     * @return string
     * @throws \ReflectionException
     */
    protected function getFixtureFileDir()
    {
        $rc = new \ReflectionClass(get_class($this));
        return dirname($rc->getFileName()) . DIRECTORY_SEPARATOR . 'fixtures';
    }

}