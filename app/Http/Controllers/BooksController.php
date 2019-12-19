<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;

class BooksController extends Controller
{
    //
    public function store(){

        Book::create(
            $this->validateRequest()
        );

        return redirect('/books');
    }

    public function update(Book $book){

        $book->update($this->validateRequest());
        return redirect($book->path());
    }

    public function validateRequest(){
        return request()->validate([
            'title' => 'required',
            'author' => 'required'
        ]);
    }

    public function destroy(Book $book){
        $book->delete();
        return redirect('/books');
    }
}
