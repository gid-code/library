<?php

namespace Tests\Unit;

use App\Author;
use App\Book;

use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class AuthorTest extends TestCase

{
    use RefreshDatabase;

    /**  */
    public function dobIsNullable(){
        // Book;
        // Author::create([
        //     'name' => 'Oliver',
        //     'dob' => '02/02/1994'
        // ]);

        // $this->assertCount(1,Author::all());
    }

    
}
