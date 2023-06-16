<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;
use App\Http\Controllers\TeammateController;
use App\Models\Teammate;
use Illuminate\Http\Request;

class TeammateControllerTest extends TestCase
{
    use WithFaker;
    use RefreshDatabase;

    public function createApplication()
    {
        $app = require __DIR__.'/../../bootstrap/app.php';
        $app->make(Kernel::class)->bootstrap();

        return $app;
    }    

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call('migrate');
    }

    public function test_index_method()
    {
        $teammate = Teammate::factory()->create([
            'student_id' => '1901051',
            'name' => "YuMin Kim",
            'profile_image' => "profile.jpg",
            'part' => "Backend",
            'description' => "A passionate developer",
            'github_link' => "https://github.com/devYuMinKim"
        ]);

        $controller = new TeammateController();
        $response = $controller->index()->getData();

        $this->assertCount(1, $response);

        $this->assertEquals($teammate->student_id, $response[0]->student_id);
        $this->assertEquals($teammate->name, $response[0]->name);
        $this->assertEquals($teammate->profile_image, $response[0]->profile_image);
        $this->assertEquals($teammate->part, $response[0]->part);
        $this->assertEquals($teammate->description, $response[0]->description);
        $this->assertEquals($teammate->github_link, $response[0]->github_link);
    }

    public function test_show_method()
    {
        // 팀원 추가 예시
        /* 
        $teammate = new Teammate([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'position' => 'Developer',
        ]);
        $teammate->save();
        */

        $response = $this->getJson('/api/teammates/1');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'position',
                'created_at',
                'updated_at'
            ]);
    }

    public function test_store_method(Request $request)
    {
        $request->validate([
            'student_id' => 'required',
            'name' => 'required',
            'profile_image' => 'required',
            'part' => 'required',
            'description' => 'required',
            'github_link' => 'required',
        ]);

        $teammate = new Teammate($request->all());
        $teammate->save();
        return response()->json($teammate);
    }

    public function test_update_method()
    {
        $teammateData = [
            'student_id' => '1901053',
            'name' => 'Test Name',
            'profile_image' => 'test.jpg',
            'part' => 'Backend',
            'description' => 'A skillful backend developer',
            'github_link' => 'https://github.com/testname',
        ];

        $teammate = new Teammate($teammateData);
        $teammate->save();

        $data = [
            'student_id' => $teammate['student_id'],
            'name' => "Updated Test",
            'profile_image' => $teammate['profile_image'],
            'part' => $teammate['part'],
            'description' => $teammate['description'],
            'github_link' => $teammate['github_link'],
        ];

        $request = new Request($data);
        $controller = new TeammateController();
        $teammateId = $teammate['id'];
        $response = $controller->update($request, $teammateId)->getData();

        dd((array) $response);
        $this->assertCount(6, (array) $response);
        $this->assertEquals($data['name'], $response->name);
    }

    public function test_destroy_method()
    {
        $teammate = Teammate::factory()->create([
            'student_id' => '1901054',
            'name' => "Delete Test",
            'profile_image' => "delete_test.jpg",
            'part' => "Designer",
            'description' => "A highly skilled designer",
            'github_link' => "https://github.com/deletetest"
        ]);

        $controller = new TeammateController();
        $teammateId = $teammate['id'];
        $controller->destroy($teammateId);

        $this->assertDatabaseMissing('teammates', ['id' => $teammate['id']]);
    }
}
