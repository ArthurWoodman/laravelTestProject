<?php

namespace Tests\Feature\Article\API\V1;

use App\Models\Article;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleTest extends TestCase
{
    use RefreshDatabase;

    private User $user;
    private Article $article;

    private array $articleData = [
        'title' => 'Title',
        'body' => 'Content',
        'user_id' => 1,
        'publication_date' => '2019-01-01',
        'slug' => 'Title'
    ];

    private array $articleData2 = [
        'title' => 'Title2',
        'body' => 'Content2',
        'user_id' => 1,
        'publication_date' => '2019-02-02',
        'slug' => 'Title2'
    ];

    private array $articleData3 = [
        'title' => 'Title3',
        'body' => 'Content3',
        'user_id' => 1,
        'publication_date' => '2019-03-03',
        'slug' => 'Title3'
    ];

    private array $credentials = [
        'email' => 'john@doe.com',
        'password' => 'secret'
    ];

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => bcrypt('secret')
        ]);

        $this->article = Article::factory()->create($this->articleData);
    }

    public function test_an_unauthenticated_user_can_see_articles()
    {
        $response = $this->getJson('/api/v1/articles');

        $this->assertGuest();
        $response->assertStatus(200);
        $this->assertJson($response->getOriginalContent());

        $responseData = $response->json();
        $responseDataArticle = $responseData['data'][0];
        unset($responseDataArticle['id'], $this->articleData['user_id']);
        $this->assertEquals($responseDataArticle, $this->articleData);
    }

    public function test_unauthenticated_user_can_not_get_tokens()
    {
        $credentials = [
            'email' => 'john@doe',
            'password' => 'secr'
        ];
        $response = $this->getTokens($credentials);

        $this->assertGuest();
        $response->assertStatus(200);
        $this->assertEmpty($response->getOriginalContent());
    }

    public function test_authenticated_user_can_get_tokens()
    {
        $response = $this->getTokens();

        $response->assertStatus(200);
        $this->assertIsArray($response->getOriginalContent());
        $this->assertArrayHasKey('adminToken', $response->getOriginalContent());
        $this->assertArrayHasKey('updateToken', $response->getOriginalContent());
        $this->assertArrayHasKey('basicToken', $response->getOriginalContent());
    }

    public function test_an_authenticated_user_can_create_an_article()
    {
        sleep(1);
        $response = $this->getTokens();

        $response->assertStatus(200);
        $this->assertIsArray($response->getOriginalContent());
        $responseData = $response->getOriginalContent();
        $this->assertArrayHasKey('updateToken', $responseData);

        $response = $this->withHeader('Authorization',  "Bearer {$responseData['updateToken']}")
            ->postJson('/api/v1/articles', $this->articleData2);

        $response->assertStatus(201);
        $this->assertJson($response->getOriginalContent());
        $this->assertDatabaseHas('articles', $this->articleData2);

        $lastArticle = Article::latest()->first();
        $this->assertEquals($lastArticle->title, $this->articleData2['title']);
        $this->assertEquals($lastArticle->body, $this->articleData2['body']);
    }

    public function test_an_authenticated_user_can_not_create_an_invalid_article()
    {
        $response = $this->getTokens();

        $response->assertStatus(200);
        $this->assertIsArray($response->getOriginalContent());
        $responseData = $response->getOriginalContent();
        $this->assertArrayHasKey('updateToken', $responseData);

        $this->articleData2['title'] = '';
        $response = $this->withHeader('Authorization',  "Bearer {$responseData['updateToken']}")
            ->postJson('/api/v1/articles', $this->articleData2);

        $response->assertStatus(422);
        $response->assertInvalid(['title']);
    }

    public function test_an_unauthenticated_user_can_not_create_an_article()
    {
        $response = $this->postJson('/get-tokens', [
            'email' => 'john@doe',
            'password' => 'secr'
        ]);

        $this->assertGuest();
        $response->assertStatus(200);

        $response = $this->post('/api/v1/articles', $this->articleData2);
        $response->assertStatus(302);
    }

    public function test_an_authenticated_user_can_update_an_article()
    {
        sleep(1);
        $response = $this->getTokens();

        $response->assertStatus(200);
        $this->assertIsArray($response->getOriginalContent());
        $responseData = $response->getOriginalContent();
        $this->assertArrayHasKey('updateToken', $responseData);

        $response = $this->withHeader('Authorization',  "Bearer {$responseData['updateToken']}")
            ->postJson('/api/v1/articles', $this->articleData2);

        $response->assertStatus(201);
        $this->assertJson($response->getOriginalContent());
        $this->assertDatabaseHas('articles', $this->articleData2);

        $lastArticle = Article::latest()->first();
        $this->assertEquals($lastArticle->title, $this->articleData2['title']);
        $this->assertEquals($lastArticle->body, $this->articleData2['body']);

        $response = $this->withHeader('Authorization',  "Bearer {$responseData['updateToken']}")
            ->putJson("/api/v1/articles/{$lastArticle->id}", $this->articleData3);
        $this->assertDatabaseHas('articles', $this->articleData3);
        $this->assertDatabaseMissing('articles', $this->articleData2);
        $this->assertDatabaseCount('articles', 2);
    }

    public function test_an_authenticated_user_can_not_update_an_article_if_not_owner()
    {
        User::factory()->create([
            'name' => 'John Doe2',
            'email' => 'john2@doe.com',
            'password' => bcrypt('secret2')
        ]);

        $credentials = [
            'email' => 'john2@doe.com',
            'password' => 'secret2'
        ];
        $response = $this->getTokens($credentials);

        $response->assertStatus(200);
        $this->assertIsArray($response->getOriginalContent());
        $responseData = $response->getOriginalContent();
        $this->assertArrayHasKey('updateToken', $responseData);

        $lastArticle = Article::latest()->first();
        $response = $this->withHeader('Authorization',  "Bearer {$responseData['updateToken']}")
            ->putJson("/api/v1/articles/{$lastArticle->id}", $this->articleData2);

        $response->assertStatus(403);
        $this->assertDatabaseHas('articles', $this->articleData);
        $this->assertDatabaseMissing('articles', $this->articleData2);
        $this->assertDatabaseCount('articles', 1);
    }

    public function test_update_an_article_validation_error()
    {
        sleep(1);
        $response = $this->getTokens();

        $response->assertStatus(200);
        $this->assertIsArray($response->getOriginalContent());
        $responseData = $response->getOriginalContent();
        $this->assertArrayHasKey('updateToken', $responseData);

        $response = $this->withHeader('Authorization',  "Bearer {$responseData['updateToken']}")
            ->postJson('/api/v1/articles', $this->articleData2);

        $response->assertStatus(201);
        $this->assertJson($response->getOriginalContent());
        $this->assertDatabaseHas('articles', $this->articleData2);

        $lastArticle = Article::latest()->first();
        $this->assertEquals($lastArticle->title, $this->articleData2['title']);
        $this->assertEquals($lastArticle->body, $this->articleData2['body']);

        $this->articleData3['title'] = '';
        $response = $this->withHeader('Authorization',  "Bearer {$responseData['updateToken']}")
            ->putJson("/api/v1/articles/{$lastArticle->id}", $this->articleData3);
        $response->assertStatus(422);
        $response->assertInvalid(['title']);
    }

    public function test_an_authenticated_user_can_delete_an_article()
    {
        sleep(1);
        $response = $this->getTokens();

        $response->assertStatus(200);
        $this->assertIsArray($response->getOriginalContent());
        $responseData = $response->getOriginalContent();
        $this->assertArrayHasKey('updateToken', $responseData);

        $lastArticle = Article::latest()->first();
        $response = $this->withHeader('Authorization',  "Bearer {$responseData['updateToken']}")
            ->deleteJson("/api/v1/articles/{$lastArticle->id}");

        $response->assertStatus(200);
        $this->assertDatabaseMissing('articles', $this->articleData);
        $this->assertDatabaseCount('articles', 0);
    }

    public function test_an_unauthenticated_user_can_not_delete_an_article()
    {
        $lastArticle = Article::latest()->first();
        $response = $this->withHeader('Authorization',  "Bearer 12345")
            ->deleteJson("/api/v1/articles/{$lastArticle->id}");

        $response->assertStatus(401);
        $this->assertDatabaseHas('articles', $this->articleData);
        $this->assertDatabaseCount('articles', 1);
    }

    public function test_an_authenticated_user_can_not_delete_an_article()
    {
        User::factory()->create([
            'name' => 'John Doe2',
            'email' => 'john2@doe.com',
            'password' => bcrypt('secret2')
        ]);

        $credentials = [
            'email' => 'john2@doe.com',
            'password' => 'secret2'
        ];
        $response = $this->getTokens($credentials);

        $response->assertStatus(200);
        $this->assertIsArray($response->getOriginalContent());
        $responseData = $response->getOriginalContent();
        $this->assertArrayHasKey('updateToken', $responseData);

        $lastArticle = Article::latest()->first();
        $response = $this->withHeader('Authorization',  "Bearer {$responseData['updateToken']}")
            ->deleteJson("/api/v1/articles/{$lastArticle->id}");

        $response->assertStatus(403);
        $this->assertDatabaseHas('articles', $this->articleData);
        $this->assertDatabaseCount('articles', 1);
    }

    private function getTokens(array|null $credentials = null)
    {
        return $this->postJson('/get-tokens', $credentials ?? $this->credentials);
    }
 }
