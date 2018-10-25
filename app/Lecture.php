<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
	public function user() {
		return $this->belongsTo('App\User');
	}

    protected $fillable = [
        'title', 'univ', 'gra', 'dep', 'number', 'date', 'lecpass',
    ];

    // 教員側の授業一覧に「出席」を押した学生の数を表示する
    public function attendCount($lec) {
        echo \DB::table('lecture_students')->where('lid',$lec->id)->count();
    }
}
