<?php

namespace App\Tests;

use function GuzzleHttp\Promise\exception_for;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Faker;

class VideosTest extends TestCase {
    protected $client;
    protected $title;
    protected $description;
    protected $thumbnail;
    protected $playlistUrl;
    protected $eventId = 1;
    protected $videoId;

    protected function setUp() {
        $this->client      = new Client(['base_uri' => $_ENV['REST_API_HOST']]);
        $faker             = Faker\Factory::create();
        $this->title       = $faker->name();
        $this->description = $faker->text();
        $this->thumbnail   = $faker->imageUrl();
        $this->playlistUrl = $faker->url;
    }

    public function testVideosAPI() {
        $options['form_params']['title']       = $this->title;
        $options['form_params']['description'] = $this->description;
        $options['form_params']['thumbnail']   = $this->thumbnail;
        $options['form_params']['playlistUrl'] = $this->playlistUrl;
        $options['form_params']['eventId']     = $this->eventId;

        $response = $this->client->post($_ENV['REST_API_VIDEOS_PATH'], $options);
        $data     = json_decode($response->getBody(), true);
        $this->videoId = $data['id'];
        fwrite(STDERR, "Created video ID " . $this->videoId."\n");

        // Make sure we get the data structure  and data we expect
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertInternalType('array', $data);
        $this->assertGreaterThan('0', count($data));
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertEquals($this->title, $data['title']);
        $this->assertArrayHasKey('description', $data);
        $this->assertEquals($this->description, $data['description']);
        $this->assertArrayHasKey('thumbnail', $data);
        $this->assertEquals($this->thumbnail, $data['thumbnail']);
        $this->assertArrayHasKey('playlist_url', $data);
        $this->assertEquals($this->playlistUrl, $data['playlist_url']);
        $this->assertArrayHasKey('event', $data);
        $this->assertArrayHasKey('id', $data['event']);
        $this->assertArrayHasKey('title', $data['event']);
        $this->assertArrayHasKey('description', $data['event']);
        $this->assertArrayHasKey('start_date', $data['event']);
        $this->assertArrayHasKey('end_date', $data['event']);

        $response = $this->client->get($_ENV['REST_API_VIDEOS_PATH'].'?name=' . $this->title);
        $data     = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        // Make sure we get the data structure we expect
        $this->assertInternalType('array', $data);
        $this->assertGreaterThan('0', count($data));
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('title', $data[0]);
        $this->assertArrayHasKey('description', $data[0]);
        $this->assertArrayHasKey('thumbnail', $data[0]);
        $this->assertArrayHasKey('playlist_url', $data[0]);
        $this->assertArrayHasKey('event', $data[0]);
        $this->assertArrayHasKey('id', $data[0]['event']);
        $this->assertArrayHasKey('title', $data[0]['event']);
        $this->assertArrayHasKey('description', $data[0]['event']);
        $this->assertArrayHasKey('start_date', $data[0]['event']);
        $this->assertArrayHasKey('end_date', $data[0]['event']);

        // Delete the test video
        $options['form_params']['id'] = $this->videoId;
        fwrite(STDERR, "Deleting video ID " . $this->videoId."\n");
        $response = $this->client->delete($_ENV['REST_API_VIDEOS_PATH'], $options);
        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function tearDown() {
        $this->client = null;
    }
}