<?php
namespace Tests\Integration\Api\v1;

use TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\Response;
use Rubel\Models\Post;
use Carbon\Carbon;

class PostTest extends TestCase
{
    use DatabaseMigrations;

    public function testIndex()
    {
        $response = $this->json('GET', route('api.posts.index'));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testStore()
    {
        $data = [
            'admin_id' => 1,
            'category_id' => 1,
            'title' => 'HereIsTitle',
            'md_content' => 'HereIsMdContent',
            'html_content' => 'HereIsHtmlContent',
            'publication_status' => 'public',
            'published_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'tags' => [
                [
                    'name' => 'HereIsTag'
                ]
            ]
        ];

        $response = $this->json('POST', route('api.posts.store'), $data, $this->getHeaders());

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testShow()
    {
        $response = $this->json('GET', route('api.posts.show', 1));

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testUpdate()
    {
        $data = [
            // 'admin_id' => 1,  FIXME set authenticated admin id  )cf. App\Repositories\Eloquent\Api\PostRepository.php
            'category_id' => 1,
            'title' => 'HereIsTitle',
            'md_content' => 'HereIsMdContent',
            'html_content' => 'HereIsHtmlContent',
            'publication_status' => 'public',
            'published_at' => Carbon::now(),
            'created_at' => Carbon::now(),
            'tags' => [
                [
                    'name' => 'HereIsTag'
                ]
            ]
        ];

        $response = $this->json('PATCH', route('api.posts.update', Post::first()->id), $data, $this->getHeaders());

        $response->assertStatus(Response::HTTP_OK);
    }

    public function testDestroy()
    {
        $response = $this->json('DELETE', route('api.posts.destroy', Post::first()->id), [], $this->getHeaders());

        $response->assertStatus(Response::HTTP_OK);
    }
}
