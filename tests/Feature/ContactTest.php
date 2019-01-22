<?php

namespace Tests\Feature;

use App\Contact;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContactTest extends TestCase
{
    use \Illuminate\Foundation\Testing\DatabaseMigrations;
    use RefreshDatabase;

    /**
     * When a pagination ajax request is received from the jquery datatable library
     * the returned data structure must be the same as specified in the library requirements:
     * https://www.datatables.net/manual/server-side#Returned-data.
     */
    public function testPaginationRequestConformityWithDatatableLibrary()
    {
        // 1. Arrange
        $contacts = factory(Contact::class, 50)->create()
            ->sortBy('id')
            ->take(10)
            ->map(function ($contact) {
                return collect($contact->toArray())
                    ->only([
                        'id',
                        'birth_date',
                        'first_name',
                        'city',
                        'email',
                        'phone',
                        'company_name',
                        'last_name'
                    ])
                    ->all();
            })
            ->toArray();
        $expectedResponsePayload = [
            'draw' => 0,
            'recordsTotal' => 50,
            'recordsFiltered' => 50,
            'data' => $contacts,
        ];
        $requestData = [
            'draw' => 0,
            'start' => 0,
            'length' => 10,
            'columns' => [
                ['data' => 'id'],
                ['data' => 'birth_date'],
                ['data' => 'first_name'],
                ['data' => 'city'],
                ['data' => 'email'],
                ['data' => 'phone'],
                ['data' => 'company_name'],
                ['data' => 'last_name'],
                ['data' => ''],
            ],
            'order' => [
                [
                    'column' => 0,
                    'dir' => 'asc'
                ],
            ],
            'search' => [
                'value' => ''
            ]

        ];
        $rawActualResponsePayload = $this->json('GET', '/api/contacts', $requestData)->getData();
        $actualResponsePayload = json_decode(json_encode($rawActualResponsePayload), true);


        // 2. Assert
        $this->assertEquals($expectedResponsePayload, $actualResponsePayload);
    }


    /**
     * When a store/update request is received
     * the data must be validated before persistence,
     * the validation rules must follow the given assignment requirements.
     */
    public function testValidationRulesConformityWithTheAssignmentRequirements()
    {
        // 1. Arrange
        $contactWithMissingEmail = factory(Contact::class)->make(['email' => null])->toArray();
        $contactWithInvalidEmail = factory(Contact::class)->make(['email' => 'kidding@me'])->toArray();
        $contactWithMissingFirstName = factory(Contact::class)->make(['first_name' => null])->toArray();
        $contactWithMissingLastName = factory(Contact::class)->make(['last_name' => null])->toArray();
        $contactWithNamesThatMustBeNormalized = factory(Contact::class)->make([
            'first_name' => 'JOhN',
            'last_name' => 'jaCoB'
        ])->toArray();

        $responseOfContactWithMissingEmail = $this->postJson('api/contacts', $contactWithMissingEmail);
        $responseOfContactWithInvalidEmail = $this->postJson('api/contacts', $contactWithInvalidEmail);
        $responseOfContactWithMissingFirstName = $this->postJson('api/contacts', $contactWithMissingFirstName);
        $responseOfContactWithMissingLastName = $this->postJson('api/contacts', $contactWithMissingLastName);

        // 2. Assert
        $responseOfContactWithMissingEmail->assertStatus(422);
        $responseOfContactWithInvalidEmail->assertStatus(422);
        $responseOfContactWithMissingFirstName->assertStatus(422);
        $responseOfContactWithMissingLastName->assertStatus(422);
        $this->assertEquals('John', $contactWithNamesThatMustBeNormalized['first_name']);
        $this->assertEquals('Jacob', $contactWithNamesThatMustBeNormalized['last_name']);
    }
}
