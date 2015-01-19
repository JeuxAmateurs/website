<?php

namespace JA\AppBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\HttpFoundation\Response;

use JA\AppBundle\DataFixtures\ORM\LoadUserData;

class UserControllerTest extends WebTestCase
{
    /**
     * @var array
     */
    protected $auth;

    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->auth = array(
            'PHP_AUTH_USER' => 'Jean-Michel',
            'PHP_AUTH_PW' => 'password',
        );

        $this->client = static::createClient();
    }

    public function authenticate()
    {
        $this->client->setServerParameters($this->auth);
    }

    public function loadUsers()
    {
        $this->loadFixtures(array('JA\AppBundle\DataFixtures\ORM\LoadUserData'));
        $users = LoadUserData::$users;

        if(empty($users))
            $this->markTestIncomplete('You must have at least one user in your fixtures');

        return $users;
    }

    protected function jsonGetUsersRequest()
    {
        $route = $this->getUrl('api_1_get_users');

        $this->client->request(
            'GET',
            $route,
            array('Accept' => 'application/json')
        );
        return $this->client->getResponse();
    }

    /**
     * Test getting the entire user collection
     */
    public function testJsonGetUsersAction()
    {
        $users = $this->loadUsers();

        $response = $this->jsonGetUsersRequest();

        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(is_array($decoded), 'The response is not an users array as expected');

        // Same number of elements
        $this->assertEquals(count($users), count($decoded), 'The number of expected users is not the same as received');

        // Same elements
        $this->assertEquals($users[0]->getId(), $decoded[0]['id'], 'The expected users are not the same as received');
    }

    /**
     * Test getting an empty user collection
     */
    public function testJsonGetEmptyUsersAction()
    {
        $this->loadFixtures(array());

        $response = $this->jsonGetUsersRequest();

        $this->assertJsonResponse($response, 200, false);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(empty($decoded) && is_array($decoded), 'The response is not an empty array as expected');
    }

    /**
     * @todo: extract this method into an external class
     */
    protected function assertJsonResponse(Response $response, $statusCode = 200, $checkValidJson = true, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }
}
