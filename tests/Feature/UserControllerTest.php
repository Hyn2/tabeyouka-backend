<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

/**
 * In this example, we're using Laravel's testing tools to write three different test cases:
 * 1. A test to ensure that a new user can be registered successfully using the register method.
 * 2. A test to ensure that a registered user can be logged in successfully using the login method.
 * 3. A test to ensure that a logged in user can be logged out successfully using the logout method.
 * 
 * Note that we're also using the RefreshDatabase and WithFaker traits to automatically refresh the database between tests and generate fake data for testing purposes.
 */
class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;
    
    public function setUp():void
    {
      parent::setUp();
      $this->artisan('migrate');
      $this->artisan('passport:install');

      User::create([
        'email' => 'test@example.com',
        'nickname' => 'test-nickname',
        'password' => bcrypt('password'),
      ]);
    }

    /** @test */
    public function test_can_register_a_new_user()
    {
      $email = $this->faker->unique()->safeEmail();
      $nickname = $this->faker->unique()->userName();
      $password = 'password';

      $response = $this->postJson('/api/register', [
        'email' => $email,
        'nickname' => $nickname,
        'password' => $password,
      ]);

      $response->assertSuccessful();
      $response->assertJson(['message' => 'User created successfully']);
      $this->assertDatabaseHas('users', ['email' => $email, 'nickname' => $nickname]);
    }

    /** @test */
    public function test_can_login_a_registered_user()
    {
      // Laravel 8 이상에서는 더 이상 factory() 함수를 사용하지 않습니다.
      // $user = factory(User::class)->create(['password' => bcrypt('password')]);
      $user = User::factory()->create([
        'password' => bcrypt('password')
      ]);

      $response = $this->postJson('/api/login', [
        'email' => $user->email,
        'password' => 'password',
      ]);

      $response->assertSuccessful();
      $response->assertJsonStructure(['user', 'token']);
      $this->assertAuthenticated();
    }

    /** @test */
    public function test_can_logout_a_logged_in_user()
    {
      $user = User::where('email', 'test@example.com')->first();
      $token = $user->createToken('auth_token')->plainTextToken;

      $response = $this->withHeaders(['Authorization' => "Bearer $token"])
                      ->postJson('/api/logout');

      $response->assertSuccessful();
      $response->assertJson(['message' => 'User logged out successfully']);
      $this->assertDatabaseMissing('personal_access_tokens', ['tokenable_id' => $user->id]);
    }

    // 테스트 진행 전의 세팅과 이후의 정리를 지속적으로 수행
    public function tearDown():void
    {
      Artisan::call('migrate:rollback'); // 각 테스트 실행 후 롤백
      parent::tearDown();
    }
}