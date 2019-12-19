<?php

namespace Tests\Feature;

use App\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function testaBookCanBeAdded(){
        $this->withoutExceptionHandling();
        $response = $this->post('/books', [
            'title' => "New book",
            'author' => "GidGid"
        ]);

        $response->assertOk();

        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function titleIsRequired()
    {
        // $this->withoutExceptionHandling();
        $response = $this->post('/books', [
            'title' => "",
            'author' => "GidGid"
        ]);

        $response->assertSessionHasErrors('title');

        // $this->assertCount(1, Book::all());
    }

    /** @test */
    public function bookCanBeUpdated() {
        $this->withoutExceptionHandling();
        $this->post('/books', [
            'title'=> 'Cool Title',
            'author' => 'GidGid'
        ]);

        $book = Book::first();

        $response = $this->patch('/books/'. $book->id,[
            'title' => 'New title',
            'author' => 'New Author'
        ]);

        $this->assertEquals('New title',Book::first()->title);
        $this->assertEquals('New Author',Book::first()->author);
    }
}
