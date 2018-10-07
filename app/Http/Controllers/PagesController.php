<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lecture;
use App\User;

class PagesController extends Controller
{
    public function show() {
    	$user = Auth::user();
		$userid = Auth::id();
		$lectures = Lecture::all();

		return view('lectures', [
			'lectures' => $lectures,
		]);
    }
}
