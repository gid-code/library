<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;

class AuthorsController extends Controller
{
    //
    public function store(){
        Author::create(
            $this->validateRequest()
        );

        // return redirect('/author');
    }

    public function validateRequest(){
        return request()->validate([
            'name' => 'required',
            'dob' => 'required'
        ]);
    }
}
