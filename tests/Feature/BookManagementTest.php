<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Book;
use App\Author;

class BookManagementTest extends TestCase
{
    use RefreshDatabase;
    /** @test */
    public function testaBookCanBeAdded(){
        $this->withoutExceptionHandling();
        $response = $this->post('/books', $this->data());

        // $response->assertOk();
        $response->assertRedirect('/books');

        $this->assertCount(1, Book::all());
    }

    /** @test */
    public function titleIsRequired()
    {
        // $this->withoutExceptionHandling();
        $response = $this->post('/books', array_merge($this->data(), ['title' => '']));

        $response->assertSessionHasErrors('title');
        // $response->assertRedirect('/books');

        // $this->assertCount(1, Book::all());
    }

    /** @test */
    public function bookCanBeUpdated() {
        $this->withoutExceptionHandling();
        $this->post('/books', [
            'title'=> 'Cool Title',
            'author_id' => 'GidGid'
        ]);

        $book = Book::first();

        $response = $this->patch('/books/'. $book->id,[
            'title' => 'New title',
            'author_id' => 'New Author'
        ]);

        $this->assertEquals('New title',Book::first()->title);
        $this->assertEquals(2,Book::first()->author_id);
        $response->assertRedirect($book->fresh()->path());
    }

    /** @test */
    public function bookCanBeDeleted(){
        $this->withoutExceptionHandling();
        $this->post('/books', [
            'title'=> 'Cool Title',
            'author_id' => 'GidGid'
        ]);

        $book = Book::first();

        $response = $this->delete('/books/'. $book->id);

        $this->assertCount(0, Book::all());

        $response->assertRedirect('/books');
    }

    /** @test */
    public function newAuthorIsAutomaticallyAdded(){
        $this->withoutExceptionHandling();
        $this->post('/books',[
            'title'=> 'Cool Title',
            'author_id' => 'GidGid'
        ]);

        $book = Book::first();
        $author = Author::first();

        $this->assertEquals($author->id,$book->author_id);
        $this->assertCount(1,Author::all());
        
    }

    private function data(){
        return [
            'title' => "New book",
            'author_id' => "GidGid"
        ];
    }
}
