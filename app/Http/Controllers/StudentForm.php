<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StudentForm extends Controller
{
    function loadView(Request $request) {
        return view('student.form');
    }
}
