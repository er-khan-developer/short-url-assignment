<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;
use App\Models\{ShortUrl,Company,User};

class MemberCanSeeOwnUrlTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  write the normal test because we put the condition on the btn default role of user is which are created on the sytem and not manage by any external pacakge.
        
     */
    public function test_example(): void
    {
        $member = User::factory()->create([
            'role' => 'Member',
        ]);

        $anotherMember = User::factory()->create([
            'role' => 'Member',
        ]);

        $company = Company::factory()->create();

        ShortUrl::factory()->create([
            'company_id'   => $company->id,
            'user_id'      => $member->id,
            'original_url' => 'https://google.com',
            'short_url'    => 'member123',
        ]);

        ShortUrl::factory()->create([
            'company_id'   => $company->id,
            'user_id'      => $anotherMember->id,
            'original_url' => 'https://github.com',
            'short_url'    => 'other123',
        ]);

        $this->actingAs($member);

        $response = $this->get(route('company.short_url'));

        $response->assertInertia(fn (Assert $page) => $page
            ->component('ShortUrl')
            ->has('shortUrls.data', 1)
            ->where('shortUrls.data.0.short_url', 'member123')
        );
    }
}
