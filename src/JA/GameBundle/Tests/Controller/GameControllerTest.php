<?php

namespace JA\GameBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Client;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;

use JA\GameBundle\DataFixtures\ORM\LoadGameData;

class GameControllerTest extends WebTestCase
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
        $domain = $this->getContainer()->getParameter('domain');
        $this->auth = array(
            'HTTP_HOST' => 'api.' . $domain,
            'PHP_AUTH_USER' => 'user',
            'PHP_AUTH_PW' => 'userpass',
        );

        $this->client = static::createClient(array(), $this->auth);
    }

    public function testJsonGetGameAction()
    {
        $this->loadFixtures(array('JA\GameBundle\DataFixtures\ORM\LoadGameData'), null, 'doctrine', ORMPurger::PURGE_MODE_TRUNCATE);
        $games = LoadGameData::$games;
        $game = array_pop($games);

        $route = $this->getUrl('api_1_get_game', array('id' => $game->getId()));

        $this->client->request(
            'GET',
            $route,
            array('Accept' => 'application/json')
        );
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['id']));
    }

    public function testJsonPostGameAction()
    {
        $this->client->request(
            'POST',
            $this->getUrl('api_1_post_game'),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            '{"name": "test"}'
        );

        $this->assertJsonResponse($this->client->getResponse(), 201, false);
    }

    // @todo: test with 4xx errors

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