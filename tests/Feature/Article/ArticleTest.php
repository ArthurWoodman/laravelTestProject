<?php

namespace Tests\Feature\Article;

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
    ];

    private array $articleData2 = [
        'title' => 'Title2',
        'body' => 'Content2',
        'user_id' => 1,
        'publication_date' => '2019-01-01',
    ];

    private array $articleData3 = [
        'title' => 'Title3',
        'body' => 'Content3',
        'user_id' => 1,
        'publication_date' => '2019-01-01',
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

    public function test_an_unauthenticated_user_can_not_see_articles_route()
    {
        $response = $this->get('/articles');

        $this->assertGuest();
        $response->assertStatus(302);
        $response->assertRedirect('http://localhost/login');
    }

    public function test_an_authenticated_user_can_see_all_articles()
    {
        $this->logIn();
        $this->assertAuthenticated();

        $response = $this->get('/articles');
        $response->assertStatus(200);
        $responseData = $response->getOriginalContent()->getData();
        $this->assertEquals($responseData['page']['props']['auth']['user']['email'], 'john@doe.com');
//        $response->assertViewHas('articles', function ($collection) {
//            return $collection->contains(Article::all());
//        });
        $this->assertSameSize($responseData['page']['props']['articles'], Article::all());
    }

    public function test_an_authenticated_user_can_read_an_article()
    {
        $this->logIn();
        $this->assertAuthenticated();

        $response = $this->get('/articles/1');
        $response->assertStatus(200);
        $responseData = $response->getOriginalContent()->getData();
        $responseArticle = $responseData['page']['props']['articles'][0];
        $this->assertEquals($this->articleData['title'], $responseArticle['title']);
        $this->assertEquals($this->articleData['body'], $responseArticle['body']);
        $this->assertEquals($this->articleData['publication_date'], $responseArticle['publication_date']);
    }

    public function test_an_authenticated_user_can_create_an_article()
    {
        $response = $this->actingAs($this->user)->post('/articles', $this->articleData);

        $this->assertAuthenticated();
        $response->assertStatus(302);
        $response->assertRedirect('/articles');
        $this->assertDatabaseHas('articles', $this->articleData);

        $lastArticle = Article::latest()->first();
        $this->assertEquals($lastArticle->title, $this->article->title);
        $this->assertEquals($lastArticle->body, $this->article->body);
    }

    public function test_article_edit_contains_correct_values()
    {
        $response = $this->actingAs($this->user)->get("/articles/{$this->article->id}/edit");
        $response->assertStatus(200);

//        can not verify since we are working with JSON passed to React...
//        $response->assertSee("value=\"{$article->title}\"");
//        $response->assertSee("value=\"{$article->body}\"", false);

        // do it in an old-fashioned way...
        $responseData = $response->getOriginalContent()->getData();
        $responseArticle = $responseData['page']['props']['articles'][0];
        $this->assertEquals($this->articleData['title'], $responseArticle['title']);
        $this->assertEquals($this->articleData['body'], $responseArticle['body']);
        $this->assertEquals($this->articleData['publication_date'], $responseArticle['publication_date']);
    }

    public function test_an_authenticated_user_can_update_an_article()
    {
        $this->logIn();
        $this->assertAuthenticated();

        $response = $this->actingAs($this->user)->put("/articles/{$this->article->id}", $this->articleData2);
        $response->assertStatus(302);
        $response->assertRedirect('/articles');
        $this->assertDatabaseHas('articles', $this->articleData2);
    }

    public function test_update_an_article_validation_error()
    {
        $this->logIn();
        $this->assertAuthenticated();

        $response = $this->actingAs($this->user)->put("/articles/{$this->article->id}", [
            'title' => '',
            'body' => ''
        ]);
        $response->assertStatus(302);
        $response->assertSessionHasErrors(['title', 'body']);
        $response->assertInvalid(['title', 'body']);
    }

    public function test_an_authenticated_user_can_delete_an_article()
    {
        $this->logIn();
        $this->assertAuthenticated();

        $response = $this->actingAs($this->user)->delete("/articles/{$this->article->id}");
        $response->assertStatus(302);
        $response->assertRedirect('articles');
        $this->assertDatabaseMissing('articles', $this->articleData);
        $this->assertDatabaseCount('articles', 0);
    }

    public function test_an_authenticated_user_can_not_edit_an_article_if_not_owner()
    {
        $user2 = User::factory()->create([
            'name' => 'John Doe2',
            'email' => 'john2@doe.com',
            'password' => bcrypt('secret2')
        ]);

        $credentials = [
            'email' => 'john2@doe.com',
            'password' => 'secret2'
        ];

        $this->logIn($credentials, $user2);

        $lastArticle = Article::latest()->first();
        $response = $this->actingAs($user2)->put("/articles/{$lastArticle->id}", $this->articleData2);
        $response->assertStatus(403);
        $this->assertDatabaseHas('articles', $this->articleData);
        $this->assertDatabaseMissing('articles', $this->articleData2);
    }

    public function test_an_authenticated_user_can_not_delete_an_article_if_not_owner()
    {
        $lastArticle = Article::latest()->first();

        $user2 = User::factory()->create([
            'name' => 'John Doe2',
            'email' => 'john2@doe.com',
            'password' => bcrypt('secret2')
        ]);

        $credentials = [
            'email' => 'john2@doe.com',
            'password' => 'secret2'
        ];
        $this->logIn($credentials, $user2);
        $this->assertAuthenticated();

        $response = $this->actingAs($user2)->delete("/articles/{$lastArticle->id}");
        $response->assertStatus(403);
        $this->assertDatabaseHas('articles', $this->articleData);
        $this->assertDatabaseCount('articles', 1);
    }

    public function test_an_unauthenticated_user_can_not_delete_an_article()
    {
        $this->assertGuest();
        $lastArticle = Article::latest()->first();
        $response = $this->delete("/articles/{$lastArticle->id}");
        $response->assertStatus(403);
        $this->assertDatabaseHas('articles', $this->articleData);
        $this->assertDatabaseCount('articles', 1);
    }

    private function logIn(
        array|null $credentials = null,
        User|null $user = null
    ): void
    {
        $this->actingAs($user ?? $this->user)->post('/login', $credentials ?? $this->credentials);
    }
 }
