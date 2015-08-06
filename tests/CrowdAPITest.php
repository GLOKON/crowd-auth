<?php

namespace GLOKON\CrowdAuth\Tests;

use \GLOKON\CrowdAuth\CrowdAPI;

class CrowdAPITest extends \PHPUnit_Framework_TestCase
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

    /** @test */
    public function it_returns_null()
    {
        $this->assertEquals($this->crowd_api->getUserGroups('php_unit_user'), null);
        $this->assertEquals($this->crowd_api->getUser('php_unit_user'), null);
        $this->assertEquals($this->crowd_api->ssoUpdateToken('php_unit_user'), null);
        $this->assertEquals($this->crowd_api->ssoGetToken('php_unit_user'), null);
        $this->assertEquals($this->crowd_api->ssoGetUser('php_unit_user'), null);
        $this->assertEquals($this->crowd_api->ssoAuthUser('php_unit_user'), null);
    }

    /** @test */
    public function it_returns_false()
    {
        $this->assertEquals($this->crowd_api->ssoInvalidateToken('php_unit_user'), false);
        $this->assertEquals($this->crowd_api->doesUserExist('php_unit_user'), false);
        $this->assertEquals($this->crowd_api->canUserLogin('php_unit_user'), false);
    }
}