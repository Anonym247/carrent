<?php

namespace Tests\Unit;

use App\Models\Car;
use App\Models\Client;
use Tests\TestCase;

class ManagementControllerTest extends TestCase
{
    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->artisan('migrate:fresh --seed');
    }

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     * @return void
     */
    public function testCars()
    {
        $response = $this->get('api/manage/cars');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'client'
            ]
        ]);
    }

    /**
     * @return void
     */
    public function testClients()
    {
        $response = $this->get('api/manage/clients');

        $response->assertStatus(200);

        $response->assertJsonStructure([
            '*' => [
                'id',
                'name',
                'car'
            ]
        ]);
    }

    /**
     * @return void
     */
    public function testSuccessAttach()
    {
        $response = $this->put('api/manage/attach', [
            'car_id' => Car::all()->random()->first()->id ?? null,
            'client_id' => Client::all()->random()->first()->id ?? null,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function testCarNotFoundWhileAttaching()
    {
        $response = $this->put('api/manage/attach', [
            'car_id' => Car::all()->random()->first()->id ?? null,
            'client_id' => -1
        ]);

        $response->assertStatus(404);

        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function testClientNotFoundWhileAttaching()
    {
        $response = $this->put('api/manage/attach', [
            'car_id' => -1,
            'client_id' => Client::all()->random()->first()->id ?? null
        ]);

        $response->assertStatus(404);

        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function testCarHasClient()
    {
        $carId = Car::all()->random()->first()->id;
        $clientId = Client::all()->random()->first()->id;

        $this->put('api/manage/attach', [
            'car_id' => $carId,
            'client_id' => $clientId,
        ]);

        $response = $this->put('api/manage/attach', [
            'car_id' => $carId,
            'client_id' => Client::query()->where('id', '!=', $clientId)->get()->random()->first()->id
        ]);

        $response->assertStatus(400);

        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function testClientHasCar()
    {
        $carId = Car::all()->random()->first()->id;
        $clientId = Client::all()->random()->first()->id;

        $this->put('api/manage/attach', [
            'car_id' => $carId,
            'client_id' => $clientId,
        ]);

        $response = $this->put('api/manage/attach', [
            'car_id' => Car::query()->where('id', '!=', $carId)->get()->random()->first()->id,
            'client_id' => $clientId,
        ]);

        $response->assertStatus(400);

        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function testDetachSuccess()
    {
        $carId = Car::all()->random()->first()->id;
        $clientId = Client::all()->random()->first()->id;

        $this->put('api/manage/attach', [
            'car_id' => $carId,
            'client_id' => $clientId,
        ]);

        $response = $this->put('api/manage/detach', [
            'car_id' => $carId,
            'client_id' => $clientId,
        ]);

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'message'
        ]);
    }

    public function testDetachingAnotherClientCar()
    {
        $carId = Car::all()->random()->first()->id;
        $clientId = Client::all()->random()->first()->id;

        $this->put('api/manage/attach', [
            'car_id' => $carId,
            'client_id' => $clientId,
        ]);

        $response = $this->put('api/manage/detach', [
            'car_id' => $carId,
            'client_id' => $clientId + 1,
        ]);

        $response->assertStatus(400);

        $response->assertJsonStructure([
            'message'
        ]);
    }
}
