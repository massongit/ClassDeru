<?php
// ログインしたときにトップページに表示するビューを表示

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Lecture;
use App\User;

class PagesController extends Controller
{
	// トップページにアクセスしたとき
    public function show() {
    	$user = Auth::user();	// ログインしているユーザを取得

		// 教員が持っている授業 と 学生が出席できる授業 の表示
		if($user->student_id == 'teacher'){
			// 教員側
			$lectures = $user->lectures;
		}else{
			// 学生側 表示されるのは同じ大学のみ
			$all = Lecture::all();
			$lectures = [];
			foreach($all as $a){
				if($a->univ == $user->univ){
					array_push($lectures, $a);
				}
			}
		}

		return view('lectures', [
			'lectures' => $lectures,
		]);
    }
}
