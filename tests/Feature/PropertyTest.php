<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PropertyTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testListAllPropertiesShouldReturnResponse200()
    {
        $propertyList = factory(\App\Models\Property::class, 5)->create();
        $response = $this->get('/api/property/');
        $response->assertJson([
                'meta' => [
                    'total_records' => 5                
                ]
            ])
            ->assertOk();
    }

    public function testListAllPropertiesNoExistingRecordsShouldReturnResponse200()
    {
        $response = $this->get('/api/property/');
        $response->assertJson([
                'meta' => [
                    'total_records' => 0                
                ]
            ])
            ->assertOk();
    }

    public function testGetSpecificPropertyIdNotProvidedShouldReturn422()
    {
        $response = $this->get('/api/property/id/');
        $response->assertJson([])
            ->assertStatus(422);
    }

    public function testGetSpecificPropertyIdProvidedIsNotANumberShouldReturn422()
    {
        $response = $this->get('/api/property/id/notanumber');
        $response->assertJson([])
            ->assertStatus(422);
    }

    public function testGetSpecificPropertyIdProvidedIsZeroShouldReturn422()
    {
        $response = $this->get('/api/property/id/0');
        $response->assertJson([])
            ->assertStatus(422);
    }

    public function testGetSpecificPropertyIdProvidedIsNegativeNumberShouldReturn422()
    {
        $response = $this->get('/api/property/id/-1');
        $response->assertJson([])
            ->assertStatus(422);
    }

    public function testGetSpecificPropertyIdProvidedButNoRecordExistsShouldReturn404()
    {
        $response = $this->get('/api/property/id/1');
        $response->assertJson([])
            ->assertNotFound();
    }

    public function testGetSpecificPropertyIdProvidedRecordExistsShouldReturn200()
    {
        $property = factory(\App\Models\Property::class)->create();
        $response = $this->get('/api/property/id/'.$property->id);
        $response->assertJson([
            'id' => $property->id,
            'streetAddress' => $property->street_address,
            'city' => $property->city,
            'postCode' => $property->post_code,
            'country' => $property->country,
            'longitude' => $property->longitude,
            'latitude' => $property->latitude
        ])
            ->assertStatus(200);
    }

    public function testRegisterNewPropertyAllRequiredInputsNotSuppliedShouldReturn422()
    {
        $response = $this->json('POST', '/api/property/', []);
        $response->assertJson(
            [
                "message" => "The given data was invalid.",
                "errors" => [
                    "streetAddress" => [
                    "The street address field is required."
                    ],
                    "city" => [
                        "The city field is required."
                    ],
                    "postCode" => [
                        "The post code field is required."
                    ],
                    "country" => [
                        "The country field is required."
                    ]
                ]
            ])
            ->assertStatus(422);
    }

    public function testRegisterNewPropertySomeOfRequiredInputsNotSuppliedShouldReturn422()
    {
        $request = [
            'streetAddress' => 'Unit 707, Parliament Suite, Hayman Street',
            'city' => 'Central City',
            'country' => 'USA',
        ];
        $response = $this->json('POST', '/api/property/', $request);
        $response->assertJson(
            [
                "message" => "The given data was invalid.",
                "errors" => [
                    "postCode" => [
                        "The post code field is required."
                    ]
                ]
            ])
            ->assertStatus(422);
    }
    
    public function testRegisterNewPropertyLongitudeLatitudeNotSuppliedShouldReturn201()
    {
        $request = [
            'streetAddress' => 'Unit 707, Parliament Suite, Hayman Street',
            'city' => 'Central City',
            'postCode' => '38922',
            'country' => 'USA',
        ];
        $response = $this->json('POST', '/api/property/', $request);
        $response->assertJson([])
            ->assertStatus(201);
        $this->assertDatabaseHas('properties', [
            'street_address' => $request['streetAddress'],
            'city' => $request['city'],
            'post_code' => $request['postCode'],
            'country' => $request['country'],
            'longitude' => null,
            'latitude' => null
        ]);
    }
    
    public function testRegisterNewPropertyLongitudeLatitudeSuppliedIsNotANumberShouldReturn422()
    {
        $request = [
            'streetAddress' => 'Unit 707, Parliament Suite, Hayman Street',
            'city' => 'Central City',
            'postCode' => '38922',
            'country' => 'USA',
            'longitude' => 'abcd',
            'latitude' => 'bcda'
        ];
        $response = $this->json('POST', '/api/property/', $request);
        $response->assertJson(
            [
                "message" => "The given data was invalid.",
                "errors" => [
                    "longitude" => [
                        "The longitude must be a valid map coordinate."
                    ],
                    "latitude" => [
                        "The latitude must be a valid map coordinate."
                    ]
                ]
            ])
            ->assertStatus(422);
        $this->assertDatabaseMissing('properties', [
            'street_address' => $request['streetAddress'],
            'city' => $request['city'],
            'post_code' => $request['postCode'],
            'country' => $request['country'],
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude']
        ]);
    }
    
    public function testRegisterNewPropertyWithValidLongitudeLatitudeSuppliedShouldReturn201()
    {
        $request = [
            'streetAddress' => 'Unit 707, Parliament Suite, Hayman Street',
            'city' => 'Central City',
            'postCode' => '38922',
            'country' => 'USA',
            'longitude' => -121.7823923,
            'latitude' => 20.4843905
        ];
        $response = $this->json('POST', '/api/property/', $request);
        $response->assertJson([])
            ->assertStatus(201);
        $this->assertDatabaseHas('properties', [
            'street_address' => $request['streetAddress'],
            'city' => $request['city'],
            'post_code' => $request['postCode'],
            'country' => $request['country'],
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude']
        ]);
    }

    public function testUpdateSpecificPropertyIdNotProvidedShouldReturn422()
    {
        $response = $this->put('/api/property/', []);
        $response->assertJson([])
            ->assertStatus(422);
    }

    public function testUpdatePropertyIdProvidedIsNotANumberShouldReturn422()
    {
        $response = $this->put('/api/property/notanumber');
        $response->assertJson([])
            ->assertStatus(422);
    }

    public function testUpdatePropertyIdProvidedIsZeroShouldReturn422()
    {
        $response = $this->put('/api/property/0');
        $response->assertJson([])
            ->assertStatus(422);
    }

    public function testUpdatePropertyIdProvidedIsNegativeNumberShouldReturn422()
    {
        $response = $this->put('/api/property/-1');
        $response->assertJson([])
            ->assertStatus(422);
    }

    public function testUpdatePropertyIdProvidedButNoRecordExistsShouldReturn404()
    {
        $response = $this->put('/api/property/1');
        $response->assertJson([])
            ->assertNotFound();
    }

    public function testUpdateExistingPropertyAllRequiredInputsNotSuppliedShouldReturn422()
    {
        $property = factory(\App\Models\Property::class)->create();
        $request = [
            'streetAddress' => '',
            'city' => '',
            'postCode' => '',
            'country' => '',
        ];
        $response = $this->json('PUT', '/api/property/'.$property->id, $request);
        
        $response->assertJson(
            [
                "message" => "The given data was invalid.",
                "errors" => [
                    "streetAddress" => [
                    "The street address field is required."
                    ],
                    "city" => [
                        "The city field is required."
                    ],
                    "postCode" => [
                        "The post code field is required."
                    ],
                    "country" => [
                        "The country field is required."
                    ]
                ]
            ])
            ->assertStatus(422);
    }

    public function testUpdateExistingPropertySomeOfRequiredInputsNotSuppliedShouldReturn422()
    {
        $property = factory(\App\Models\Property::class)->create();
        $request = [
            'streetAddress' => 'Unit 707, Parliament Suite, Hayman Street',
            'city' => '',
            'postCode' => '',
            'country' => 'USA',
        ];
        $response = $this->json('PUT', '/api/property/'.$property->id, $request);
        
        $response->assertJson(
            [
                "message" => "The given data was invalid.",
                "errors" => [
                    "city" => [
                        "The city field is required."
                    ],
                    "postCode" => [
                        "The post code field is required."
                    ]
                ]
            ])
            ->assertStatus(422);
    }
    
    public function testUpdateExistingPropertyLongitudeLatitudeNotSuppliedShouldReturn201()
    {
        $property = factory(\App\Models\Property::class)->create();
        $request = [
            'streetAddress' => 'Unit 707, Parliament Suite, Hayman Street',
            'city' => 'Central City',
            'postCode' => '38922',
            'country' => 'USA',
        ];
        $response = $this->put('/api/property/'.$property->id, $request);
        $response->assertJson([])
            ->assertStatus(201);
        $this->assertDatabaseHas('properties', [
            'street_address' => $request['streetAddress'],
            'city' => $request['city'],
            'post_code' => $request['postCode'],
            'country' => $request['country'],
            'longitude' => null,
            'latitude' => null
        ]);
    }
    
    public function testUpdateSpecificPropertyLongitudeLatitudeSuppliedIsNotANumberShouldReturn422()
    {
        $property = factory(\App\Models\Property::class)->create();
        $request = [
            'streetAddress' => 'Unit 707, Parliament Suite, Hayman Street',
            'city' => 'Central City',
            'postCode' => '38922',
            'country' => 'USA',
            'longitude' => 'abcd',
            'latitude' => 'bcda'
        ];
        $response = $this->json('PUT', '/api/property/'.$property->id, $request, $request);
        $response->assertJson(
            [
                "message" => "The given data was invalid.",
                "errors" => [
                    "longitude" => [
                        "The longitude must be a valid map coordinate."
                    ],
                    "latitude" => [
                        "The latitude must be a valid map coordinate."
                    ]
                ]
            ])
            ->assertStatus(422);
        $this->assertDatabaseMissing('properties', [
            'street_address' => $request['streetAddress'],
            'city' => $request['city'],
            'post_code' => $request['postCode'],
            'country' => $request['country'],
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude']
        ]);
    }
    
    public function testUpdatePropertyWithValidLongitudeLatitudeSuppliedShouldReturn201()
    {
        $property = factory(\App\Models\Property::class)->create();
        $request = [
            'streetAddress' => 'Unit 707, Parliament Suite, Hayman Street',
            'city' => 'Central City',
            'postCode' => '38922',
            'country' => 'USA',
            'longitude' => -121.7823923,
            'latitude' => 20.4843905
        ];
        $response = $this->json('PUT', '/api/property/'.$property->id, $request);
        $response->assertJson([])
            ->assertStatus(201);
        $this->assertDatabaseHas('properties', [
            'street_address' => $request['streetAddress'],
            'city' => $request['city'],
            'post_code' => $request['postCode'],
            'country' => $request['country'],
            'longitude' => $request['longitude'],
            'latitude' => $request['latitude']
        ]);
    }
}
