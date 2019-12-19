<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Author;
use Carbon\Carbon;

class AuthorManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function authorCanBeCreated(){
        $this->withoutExceptionHandling();

        $this->post('/author',[
            'name' => 'New Author',
            'dob' => '02/02/1994'
        ]);

        $author = Author::all();

        $this->assertCount(1,$author);
        $this->assertInstanceOf(Carbon::class,$author->first()->dob);
    }
}
