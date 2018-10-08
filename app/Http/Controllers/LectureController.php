<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Lecture;


class LectureController extends Controller
{
	// 教員が出席者数を確認
    public function showStudent($lecture) {
    	// Lectureモデルから$lecture(番号)のlectureを検索して取得
    	$attendall = Lecture::where('id', $lecture)
    		->value('attendstudent');

    	// 授業名を取得
    	$lectitle = Lecture::where('id', $lecture)
    		->value('title');

    	// 授業の全履修者数を取得
    	$lecnum = Lecture::where('id', $lecture)
    		->value('number');

    	return view('showStudent', ['attendall' => $attendall,
    								'lectitle' => $lectitle,
    								'lecnum' => $lecnum,
    								]);
    }

    // 学生が出席をクリックしたとき
    public function clickUser($lecture) {
    	$user = Auth::user();

    	// Lectureモデルから$lecture(番号)のlectureを検索して取得
    	$setpos = Lecture::where('id', $lecture)
    		->value('attendstudent');

    	$setpos = $setpos.",".$user->name;
    	$setpos = $setpos.",".$user->student_id;

    	// 取得したlectureのattendstudentにuserのnameとstudent_idを格納
    	Lecture::where('id', $lecture)
    		->update([
    			'attendstudent' => $setpos
    		]);

    	echo Lecture::where('id', $lecture)
    		->value('attendstudent');
  
    	//return redirect('/');
    }
}
