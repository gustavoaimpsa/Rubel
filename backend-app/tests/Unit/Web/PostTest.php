<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class PostTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $response = $this->get(route('web.posts'));

        $response->assertStatus(200);
    }

    public function testShow()
    {
        $post = factory(App\Models\Post::class)->create(
            [
                'publication_status' => 'public'
            ]
        );

        $response = $this->get(route('web.post', $post->title));

        $response->assertStatus(200);
    }
}
