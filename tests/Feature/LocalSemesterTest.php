<?php

namespace Tests\Feature;

use App\Models\LocalSemester;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LocalSemesterTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_article_successfully()
    {
        $localSemester = LocalSemester::factory()->create();
        $response = $this->getJson("/api/localsemester");

        $response->assertStatus(200)
        ->assertJson([
                    'id'=>$localSemester->id,
                    'article'=>$localSemester->article,
        ]);
    }

    public function test_edit_article_successfully()
    {
        $localSemester = LocalSemester::factory()->create();
        
        $data = [
            'article' => 'Local Semester is ...',
        ];
        $response = $this->putJson("/api/localsemester", $data);

        $response->assertStatus(200)
        ->assertJson(['message'=>'Edit article successfully']);
    }

}
