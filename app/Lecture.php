<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
	public function user() {
		return $this->belongsTo('App\User');
	}

    protected $fillable = [
        'title', 'univ', 'gra', 'dep', 'number', 'date',
    ];

    // 教員側の授業一覧に「出席」を押した学生の数を表示する
    public function attendCount($lec) {
        /*
    	$attendall =$lec['attendstudent'];

    	// 出席した学生を , で分割
    	$student = explode(",", $attendall);

    	// 出席者数を取得
    	$num = (count($student)-1)/2;
        */

        echo \DB::table('lecture_students')->where('lid',$lec->id)->count();
    }
}
