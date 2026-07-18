<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\{ShortUrl};

class ShortUrlPublicallyNotAllowedTest extends TestCase
{
    /**
     * A basic feature short url not open directally.
     */
    public function test_short_url_publically_not_allowed(): void
    {
        $shortUrl = ShortUrl::factory()->create([
                'short_url'   => 'abc123',
                'original_url' => 'https://www.google.com',
        ]);

       $response = $this->get('/company/s/abc123');

       $response->assertRedirect('https://www.google.com');
    }
}
