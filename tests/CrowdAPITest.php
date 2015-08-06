<?php

/*
 * This file is part of CrowdAuth
 *
 * (c) Daniel McAssey <hello@glokon.me>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use GLOKON\CrowdAuth\Api\CrowdAPI;

class CrowdAPITest extends \Orchestra\Testbench\TestCase
{

    /**
     * @var Crowd API
     */
    protected $crowd_api;

    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
        $this->crowd_api = new CrowdAPI();
    }

    /**
     * Get package providers.  At a minimum this is the package being tested, but also
     * would include packages upon which our package depends, e.g. Cartalyst/Sentry
     * In a normal app environment these would be added to the 'providers' array in
     * the config/app.php file.
     *
     * @return array
     */
    protected function getPackageProviders()
    {
        return array(
            'GLOKON\CrowdAuth\CrowdAuthServiceProvider',
        );
    }

    /** @test */
    public function it_initializes()
    {
        $this->assertInstanceOf('\GLOKON\CrowdAuth\Api\CrowdAPI', $this->crowd_api);
    }

    /** @test */
    public function it_returns_null()
    {
        $this->assertEquals($this->crowd_api->getUserGroups('php_unit_user'), null);
        $this->assertEquals($this->crowd_api->getUser('php_unit_user'), null);
        $this->assertEquals($this->crowd_api->ssoUpdateToken('php_unit_user', '0.0.0.0'), null);
        $this->assertEquals($this->crowd_api->ssoGetToken('php_unit_user'), null);
        $this->assertEquals($this->crowd_api->ssoGetUser('php_unit_user', 'php_unit_token'), null);
        $this->assertEquals($this->crowd_api->ssoAuthUser('php_unit_user', '0.0.0.0'), null);
    }

    /** @test */
    public function it_returns_false()
    {
        $this->assertEquals($this->crowd_api->ssoInvalidateToken('php_unit_user'), false);
        $this->assertEquals($this->crowd_api->doesUserExist('php_unit_user'), false);
        $this->assertEquals($this->crowd_api->canUserLogin('php_unit_user'), false);
    }
}