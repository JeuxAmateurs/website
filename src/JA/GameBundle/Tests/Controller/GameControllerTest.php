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

    public function loadGames()
    {
        $this->loadFixtures(array('JA\GameBundle\DataFixtures\ORM\LoadGameData'), null, 'doctrine', ORMPurger::PURGE_MODE_TRUNCATE);
        $games = LoadGameData::$games;

        if(empty($games))
            $this->markTestIncomplete('You must have at least one game in your fixtures');

        return $games;
    }

    protected function jsonGetGamesRequest()
    {
        $route = $this->getUrl('api_1_get_games');

        $this->client->request(
            'GET',
            $route,
            array('Accept' => 'application/json')
        );
        return $this->client->getResponse();
    }

    /**
     * Test getting the entire game collection
     */
    public function testJsonGetGamesAction()
    {
        $games = $this->loadGames();

        $response = $this->jsonGetGamesRequest();

        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(is_array($decoded), 'The response is not an games array as expected');

        // Same number of elements
        $this->assertEquals(count($games), count($decoded), 'The number of expected games is not the same as received');

        // Same elements
        $this->assertEquals($games[0]->getId(), $decoded[0]['id'], 'The expected games are not the same as received');
    }

    /**
     * Test getting an empty game collection
     */
    public function testJsonGetEmptyGamesAction()
    {
        $this->loadFixtures(array(), null, 'doctrine', ORMPurger::PURGE_MODE_TRUNCATE);

        $response = $this->jsonGetGamesRequest();

        $this->assertJsonResponse($response, 200, false);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(empty($decoded) && is_array($decoded), 'The response is not an empty array as expected');
    }

    protected function jsonGetGameRequest($parameters)
    {
        $route = $this->getUrl('api_1_get_game', $parameters);

        $this->client->request(
            'GET',
            $route,
            array('Accept' => 'application/json')
        );
        return $this->client->getResponse();
    }

    /**
     * Test getting a game from the loaded fixtures
     */
    public function testJsonGetGameAction()
    {
        $games = $this->loadGames();
        $game = array_pop($games);

        $response = $this->jsonGetGameRequest(array('slug' => $game->getSlug()));

        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['slug']));
    }

    /**
     * Test failing at getting a game (404 Not Found expected)
     */
    public function testJsonGetNotFoundGameAction()
    {
        // emptying database, so we're sure we're not getting anything
        $this->loadFixtures(array(), null, 'doctrine', ORMPurger::PURGE_MODE_TRUNCATE);

        $response = $this->jsonGetGameRequest(array('slug' => 'my-super-non-existent-game'));

        $this->assertJsonResponse($response, 404);
        $content = $response->getContent();
    }

    protected function jsonPostGameRequest($body = '')
    {
        $this->client->request(
            'POST',
            $this->getUrl('api_1_post_game'),
            array(),
            array(),
            array('CONTENT_TYPE' => 'application/json'),
            $body
        );
        return $this->client->getResponse();
    }

    /**
     * Test success at posting new game
     */
    public function testJsonPostGameAction()
    {
        $response = $this->jsonPostGameRequest('{"game":{"name": "test"}}');

        $this->assertJsonResponse($response, 201, false);
    }

    /**
     * Test fail at posting new game
     */
    public function testJsonPostGameFail422Action()
    {
        $response = $this->jsonPostGameRequest('{"game":{"jambon": "beurre"}}'); // content is not valid

        $this->assertJsonResponse($response, 422);
    }

    /**
     * Test fail at posting some random json
     */
    public function testJsonPostGameFailRandomJsonAction()
    {
        $response = $this->jsonPostGameRequest('{"jambon": "beurre"}'); // content is not valid

        $this->assertJsonResponse($response, 422);
    }

    /**
     * Test fail at posting new game with incomprehensible body
     */
    public function testJsonPostGameFail400Action()
    {
        $response = $this->jsonPostGameRequest('yolo argh'); // content is really not valid

        $this->assertJsonResponse($response, 400);
    }

    /**
     * Test deleting a game
     */
    public function testJsonDeleteGame()
    {
        $games = $this->loadGames();
        $game = array_pop($games);

        $this->client->request(
            'DELETE',
            $this->getUrl('api_1_delete_game', array('slug' => $game->getSlug()))
        );
        $response = $this->client->getResponse();

        $this->assertEquals($response->getStatusCode(), 204, 'The response must be 204 No Content');

        // Verify that the game was well deleted
        $this->client->request(
            'GET',
            $this->getUrl('api_1_get_game', array('slug' => $game->getSlug()))
        );
        $response = $this->client->getResponse();

        $this->assertEquals($response->getStatusCode(), 404, 'The game has not been deleted as expected');
    }

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