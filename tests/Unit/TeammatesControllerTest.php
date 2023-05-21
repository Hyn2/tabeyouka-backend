<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Contracts\Console\Kernel;
use App\Http\Controllers\TeammatesController;
use App\Models\Teammate;

class TeammatesControllerTest extends TestCase
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

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_index_method()
    {
        // 테스트용 팀원 데이터 생성
        $teammate = Teammate::factory()->create([
            'student_id' => '1901051',
            'name' => "YuMin Kim",
            'profile_image' => "profile.jpg",
            'part' => "Backend",
            'description' => "A passionate developer",
            'github_link' => "https://github.com/devYuMinKim"
        ]);

        // index 메서드 호출후 JSON 응답 얻기
        $controller = new TeammatesController();
        $response = $controller->index()->getData();

        // 반환된 JSON 데이터 개수 확인
        $this->assertCount(1, $response);

        // 반환된 속성 값 확인
        $this->assertEquals($teammate->student_id, $response[0]->student_id);
        $this->assertEquals($teammate->name, $response[0]->name);
        $this->assertEquals($teammate->profile_image, $response[0]->profile_image);
        $this->assertEquals($teammate->part, $response[0]->part);
        $this->assertEquals($teammate->description, $response[0]->description);
        $this->assertEquals($teammate->github_link, $response[0]->github_link);
    }
}
