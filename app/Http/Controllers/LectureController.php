<?php
//	学生が出席ボタンを押したとき, 教員が確認ボタンを押したときに
//	呼ばれるコントローラー

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Lecture;


class LectureController extends Controller
{
	// 教員が授業を追加するとき
	/*
	public function addLecture($request) {
		$lecture = new Lecture;
		$lecture->title = $request->title;
		$lecture->univ = $request->univ;
		$lecture->gra = $request->gra;
		$lecture->dep = $request->dep;
		$lecture->number = $request->number;
		$lecture->date = $request->date;
		$lecture->user_id = $request->user()->id;
		$lecture->save();

		return redirect('/');
	}
	*/

	// 教員が出席者を確認するとき
    public function showStudent($lecture) {
    	// Lectureモデルから$lecture(番号)のlectureを検索して取得
    	// 出席した学生一覧
    	$attendall = Lecture::where('id', $lecture)
    		->value('attendstudent');

    	// 授業名を取得
    	$lectitle = Lecture::where('id', $lecture)
    		->value('title');

    	// 授業の全履修者数を取得
    	$lecnum = Lecture::where('id', $lecture)
    		->value('number');

    	// ユーザーが教員のときのみ, 出席管理画面にアクセスできる
    	if(Auth::user()->student_id == 'teacher'){
    		// showStudentを表示
	    	return view('showStudent', ['attendall' => $attendall,
									'lectitle' => $lectitle,
									'lecnum' => $lecnum,
									]);
    	}else{
    		return redirect('/');
    	}
    }

    // 学生が出席をクリックしたとき
    public function clickUser($lecture) {
    	$user = Auth::user();

    	// Lectureモデルから$lecture(番号)のlectureを検索して取得
    	$setpos = Lecture::where('id', $lecture)
    		->value('attendstudent');

    	// ,名前,学生番号,名前,学生番号... の順に格納していく
    	$setpos = $setpos.",".$user->student_id;
    	$setpos = $setpos.",".$user->name;

    	// 取得したlectureのattendstudentにuserのnameとstudent_idを格納
    	Lecture::where('id', $lecture)
    		->update([
    			'attendstudent' => $setpos
    		]);

    	return redirect('/');
    }
}
