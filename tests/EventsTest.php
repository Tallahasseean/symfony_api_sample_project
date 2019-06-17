<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;
use Faker;

class EventsTest extends TestCase {
    protected $client;
    protected $eventId;
    protected $title;
    protected $description;
    protected $startDate;
    protected $endDate;

    protected function setUp() {
        $this->client      = new Client(['base_uri' => $_ENV['REST_API_HOST']]);
        $faker             = Faker\Factory::create();
        $this->title       = $faker->name();
        $this->description = $faker->text();
        $this->startDate   = $faker->date($format = 'Y-m-d', $max = 'now');
        $this->endDate     = $faker->date($format = 'Y-m-d', $max = 'now');
    }

    public function testEventsAPI() {
        $options['form_params']['title']       = $this->title;
        $options['form_params']['description'] = $this->description;
        $options['form_params']['startDate']   = $this->startDate;
        $options['form_params']['endDate']     = $this->endDate;

        $response      = $this->client->post($_ENV['REST_API_EVENTS_PATH'], $options);
        $data          = json_decode($response->getBody(), true);
        $this->eventId = $data['id'];
        fwrite(STDERR, "Created event ID " . $this->eventId . "\n");

        // Make sure we get the data structure  and data we expect
        $this->assertEquals(201, $response->getStatusCode());
        $this->assertArrayHasKey('id', $data);
        $this->assertArrayHasKey('title', $data);
        $this->assertArrayHasKey('description', $data);
        $this->assertArrayHasKey('start_date', $data);
        $this->assertArrayHasKey('end_date', $data);

        $response = $this->client->get($_ENV['REST_API_EVENTS_PATH'].'?name=' . $this->title);
        $data     = json_decode($response->getBody(), true);

        $this->assertEquals(200, $response->getStatusCode());
        // Make sure we get the data structure we expect
        $this->assertInternalType('array', $data);
        $this->assertArrayHasKey('id', $data[0]);
        $this->assertArrayHasKey('title', $data[0]);
        $this->assertArrayHasKey('description', $data[0]);
        $this->assertArrayHasKey('start_date', $data[0]);
        $this->assertArrayHasKey('end_date', $data[0]);

        // Delete the test event
        $options['form_params']['id'] = $this->eventId;
        fwrite(STDERR, "Deleting event ID " . $this->eventId."\n");
        $response = $this->client->delete($_ENV['REST_API_EVENTS_PATH'], $options);
        $this->assertEquals(204, $response->getStatusCode());
    }

    protected function tearDown() {
        $this->client = null;
    }
}