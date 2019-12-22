<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Book;
use App\User;
use App\Reservation;

class BookCheckoutTest extends TestCase
{

    use RefreshDatabase;
    /**
     * @test
     */
    public function bookCanBeCheckoutBySignedInUser()
    {
        $book = \factory(Book::class)->create();

        $this->actingAs($user = \factory(User::class)->create())
            ->post('/checkout/'. $book->id);

        $this->assertCount(1,Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_out_at);
    }

    /** @test */
    public function onlySignedInUsersCanCheckoutBook(){
        // $this->withoutExceptionHandling();
        $book = \factory(Book::class)->create();

        $this->post('/checkout/'. $book->id)
            ->assertRedirect('/login');

        $this->assertCount(0,Reservation::all());
    }

    /** @test */
    public function onlyRealBooksCanBeCheckedout(){
        $this->actingAs($user = \factory(User::class)->create())
            ->post('/checkout/123')
            ->assertStatus(404);

        $this->assertCount(0,Reservation::all());
    }

    /**
     * @test
     */
    public function bookCanBeCheckinBySignedInUser()
    {
        // $this->withoutExceptionHandling();
        $book = \factory(Book::class)->create();
        $user = \factory(User::class)->create();

        $this->actingAs($user)
            ->post('/checkout/'. $book->id);

        $this->actingAs($user)
            ->post('/checkin/'. $book->id);

        $this->assertCount(1,Reservation::all());
        $this->assertEquals($user->id, Reservation::first()->user_id);
        $this->assertEquals($book->id, Reservation::first()->book_id);
        $this->assertEquals(now(), Reservation::first()->checked_in_at);
    }

    /** @test */
    public function onlySignedInUsersCanCheckinBook(){
        // $this->withoutExceptionHandling();
        $book = \factory(Book::class)->create();

        $this->actingAs(\factory(User::class)->create())
            ->post('/checkout/'. $book->id);

        Auth::logout();

        $this->post('/checkin/'. $book->id)
            ->assertRedirect('/login');

        $this->assertCount(1,Reservation::all());
        $this->assertNull(Reservation::first()->checked_in_at);
    }

    /** @test */
    public function exceptionThrowIfBookIsNotCheckedOutFirst(){
        $this->withoutExceptionHandling();
        $book = \factory(Book::class)->create();
        $user = \factory(User::class)->create();

        $this->actingAs($user)
            ->post('/checkin/'. $book->id)
            ->assertStatus(404);

        $this->assertCount(0,Reservation::all());
    }

}
