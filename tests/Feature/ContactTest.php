<?php

namespace Tests\Feature;

use App\User;
use App\Contact;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;

class ContactTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function data()
    {
        return [
            'name' => 'Test name',
            'email' => 'email@mail.ru',
            'birthday' => '05/14/1988',
            'company' => 'ABC',
            'api_token' => $this->user->api_token
        ];
    }

    /** @test */
    public function an_unauthenticated_user_should_redirected_to_login()
    {
        $response = $this->post('/api/contacts',
            array_merge($this->data(), ['api_token' => '']));

        $response->assertRedirect('/login');

        $this->assertCount(0, Contact::all());
    }

    /**
     * @test
     */
    public function an_unauthenticated_user_contact_can_be_contact()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('api/contacts', $this->data());

        $contact = Contact::first();

        $this->assertEquals($this->data()['name'], $contact->name);
        $this->assertEquals($this->data()['email'], $contact->email);
        $this->assertEquals($this->data()['birthday'], $contact->birthday->format('m/d/Y'));
        $this->assertEquals($this->data()['company'], $contact->company);
        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertJson([
            'data' => [
                'contact_id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'birthday' => $contact->birthday->format('m/d/y'),
                'company' => $contact->company,
            ],
            'links' => [
                'self' => $contact->path(),
            ]
        ]);

    }

    /** @test */
    public function fields_are_required()
    {

        collect(['name', 'email', 'birthday', 'company'])
            ->each(function ($field) {

                $response = $this->post('/api/contacts',
                    array_merge($this->data(), [$field => '']));

                $response->assertSessionHasErrors($field);
                $this->assertCount(0, Contact::all());
            });
    }

    /** @test */
    public function a_contact_can_be_retrieved()
    {
        $contact = factory(Contact::class)->create(['user_id' => $this->user->id]);

        $response = $this->get('/api/contacts/' . $contact->id . '?api_token=' . $this->user->api_token);

        $response->assertJson([
            'data' => [
                'contact_id' => $contact->id,
                'name' => $contact->name,
                'email' => $contact->email,
                'birthday' => $contact->birthday->format('m/d/y'),
                'company' => $contact->company,
            ]
        ]);
    }

    /** @test */
    public function a_contact_can_be_patched()
    {
        $contact = factory(Contact::class)->create(['user_id' => $this->user->id]);

        $response = $this->patch('/api/contacts/' . $contact->id, $this->data());

        $contact = $contact->fresh();

        $this->assertEquals($this->data()['name'], $contact->name);
        $this->assertEquals($this->data()['email'], $contact->email);
        $this->assertEquals($this->data()['birthday'], $contact->birthday->format('m/d/Y'));
        $this->assertEquals($this->data()['company'], $contact->company);

        $response->assertStatus(Response::HTTP_OK);
        $response->assertJson([
            'data' => [
                'contact_id' => $contact->id,
            ],
            'links' => [
                'self' => $contact->path(),
            ]
        ]);
    }


    /** @test */
    public function only_the_owner_of_the_contact_can_patch_the_contact()
    {
        $contact = factory(Contact::class)->create();

        $anotherUser = factory(User::class)->create();

        $response = $this->patch('/api/contacts/' . $contact->id,
            array_merge($this->data(), ['api_token' => $anotherUser->api_token]));

        $response->assertStatus(403);
    }

    /** @test */
    public function a_contact_can_be_deleted()
    {
        $contact = factory(Contact::class)->create(['user_id' => $this->user->id]);

        $response = $this->delete('/api/contacts/' . $contact->id,
            ['api_token' => $this->user->api_token]);

        $this->assertCount(0, Contact::all());

        $response->assertStatus(Response::HTTP_NO_CONTENT);
    }

    /** @test */
    public function only_the_owner_can_delete_the_contact()
    {
        $contact = factory(Contact::class)->create();

        $anotherUser = factory(User::class)->create();

        $response = $this->delete('/api/contacts/' . $contact->id,
            ['api_token' => $this->user->api_token]);

        $response->assertStatus(403);
    }

    /** @test */
    public function a_list_of_contacts_can_be_fetched_for_the_authenticated_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();
        $anotherUser = factory(User::class)->create();

        $contact = factory(Contact::class)->create(['user_id' => $user->id]);
        $anotherContact = factory(Contact::class)->create(['user_id' => $anotherUser->id]);

        $response = $this->get('/api/contacts/' . '?api_token=' . $user->api_token);

        $response->assertJsonCount(1)
            ->assertJson([
                'data' => [
                    [
                        "data" => [
                            'contact_id' => $contact->id
                        ]
                    ]
                ]
            ]);
    }


    /** @test */
    public function only_the_users_contacts_can_be_retrieved()
    {
        $contact = factory(Contact::class)->create(['user_id' => $this->user->id]);

        $anotherUser = factory(User::class)->create();

        $response = $this->get('/api/contacts/' . $contact->id . '?api_token=' . $anotherUser->api_token);

        $response->assertStatus(403);
    }
}
