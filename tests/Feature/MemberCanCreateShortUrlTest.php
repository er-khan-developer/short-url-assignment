<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\{User,Company};

class MemberCanCreateShortUrlTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_member_can_create_short_url(): void
    {
        // write the normal test because we put the condition on the btn default role of user is which are created on the sytem and not manage by any external pacakge.
        
        $admin = User::factory()->create([
            'role' => 'Admin',
        ]);

        $company = Company::factory()->create();

        $this->actingAs($admin);

        $response = $this->post('/company/' . $company->id . '/generate-short-url', [
            'longUrl' => 'https://google.com',
        ]);

         $response->assertCreated()
         ->assertJson([
             'success' => true,
               'message' => 'Generate Url SuccessFully.',
        ]);
    }
}
