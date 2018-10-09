<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lecture;
use App\User;

class PagesController extends Controller
{
	// トップページにアクセスしたとき
    public function show() {
    	$user = Auth::user();
		$userid = Auth::id();

		// 教員が持っている授業 と 学生が出席できる授業 の表示
		if($user->student_id == 'teacher'){
			$lectures = $user->lectures;
		}else{
			$lectures = Lecture::all();
		}

		return view('lectures', [
			'lectures' => $lectures,
		]);
    }
}
