<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\{User,Company};

class SuperAdminNotCreatedShortUrlTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_super_admin_cannot_create_short_url(): void
    {
        // write the normal test because we put the condition on the btn default role of user is which are created on the sytem and not manage by any external pacakge.

         $superAdmin = User::factory()->create([
            'role' => 'SuperAdmin'
        ]);

        $company = Company::factory()->create();

        $this->actingAs($superAdmin);

        $response = $this->post('/company/' . $company->id . '/generate-short-url', [
            'longUrl' => 'https://google.com',
        ]);

        $response->assertForbidden();
    }
}
