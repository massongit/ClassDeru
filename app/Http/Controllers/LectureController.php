<?php
//	学生が出席ボタンを押したとき, 教員が確認ボタンを押したときに
//	呼ばれるコントローラー

namespace App\Http\Controllers;

use App\Lecture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;


class LectureController extends Controller
{
    // 教員が授業を追加する
    public function addLecture(Request $request)
    {
        // 記入漏れがあった場合の処理
        if ($request->title == "" or $request->univ == "" or $request->gra == "" or $request->dep == "" or $request->number == "" or $request->date == "") {
            return redirect('/user')->with('my_status_2', __('未記入項目があります。'));
        } else {
            // 正しく記入されている場合の処理
            $lecture = new Lecture;

            $lecture->title = $request->title;
            $lecture->univ = $request->univ;
            $lecture->gra = $request->gra;
            $lecture->dep = $request->dep;
            $lecture->number = $request->number;
            $lecture->date = $request->date;

            // パスワードが記入されていたら代入する
            if ($request->lecpass != "") {
                $lecture->lecpass = $request->lecpass;
            } else {
                $lecture->lecpass = "";
            }

            $lecture->user_id = $request->user()->id;
            $lecture->save();

            return redirect('/user');
        }
    }


    // 教員が出席者を確認するとき
    public function showStudent($lecture)
    {
        // 出席した学生数
        $lecnum = \DB::table('lecture_students')->where('lid', $lecture)->count();

        // 登録履修者数
        $allnum = \DB::table('lectures')->where('id', $lecture)->value('number');

        // 授業名を取得
        $lectitle = Lecture::where('id', $lecture)
            ->value('title');

        // 出席した学生の名前と学生番号を取得
        $attendallname = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sname');
        $attendallid = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sid');


        // ユーザーが教員のときのみ, 出席管理画面にアクセスできる
        if (Auth::user()->student_id == 'teacher') {
            // showStudentを表示
            return view('showStudent', ['attendallname' => $attendallname,
                'attendallid' => $attendallid,
                'lectitle' => $lectitle,
                'lecnum' => $lecnum,
                'lecture' => $lecture,
                'allnum' => $allnum,
            ]);
        } else {
            return redirect('/');
        }
    }


    // 教員が出席者データをcsvでダウンロードするとき
    public function downloadCSV($lecture)
    {
        // 出席者を取得
        $attendallname = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sname');
        $attendallid = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sid');

        // 全出席者数を取得
        $syuseki_num = \DB::table('lecture_students')->where('lid', $lecture)->count();

        // 履修者数を取得
        $risyu_num = \DB::table('lectures')->where('id', $lecture)->value('number');

        // 授業名を取得
        $title = Lecture::where('id', $lecture)
            ->value('title');

        // 時刻取得
        $time = new Carbon();
        $time->setTimezone('Asia/Tokyo');

        # 文字コード変換
        mb_convert_variables('SJIS-win', 'UTF-8', $attendallname);
        mb_convert_variables('SJIS-win', 'UTF-8', $attendallid);

        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename={$title}_出席者.csv");

        $stream = fopen('php://output', 'w');

        // 時刻付与
        $t1 = $time->year;
        $t2 = $time->month;
        $t3 = $time->day;
        $t4 = $time->hour;
        $t5 = $time->minute;
        $now = $t1 . "年" . $t2 . "月" . $t3 . "日" . $t4 . "時" . $t5 . "分";

        // 出席者数
        $num = $syuseki_num . "/" . $risyu_num . " 人";

        mb_convert_variables('SJIS-win', 'UTF-8', $num);
        mb_convert_variables('SJIS-win', 'UTF-8', $title);
        mb_convert_variables('SJIS-win', 'UTF-8', $now);
        $data = [$title, $num, $now];
        fputcsv($stream, $data);

        // 改行させる
        $data = [""];
        fputcsv($stream, $data);

        // 出席番号,学生番号,名前 で格納していく
        for ($i = 0; $i < count($attendallname); $i++) {
            $data = [$i + 1, $attendallid[$i], $attendallname[$i]];
            fputcsv($stream, $data);
        }
    }


    // 教員が出席者データをtxtでダウンロードするとき
    public function downloadTxt($lecture)
    {
        // 出席者の名前と学生番号を取得
        $attendallname = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sname');
        $attendallid = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sid');

        // 全出席者数を取得
        $syuseki_num = \DB::table('lecture_students')->where('lid', $lecture)->count();

        // 履修者数を取得
        $risyu_num = \DB::table('lectures')->where('id', $lecture)->value('number');

        // 授業名を取得
        $title = Lecture::where('id', $lecture)
            ->value('title');

        // 時刻取得
        $time = new Carbon();
        $time->setTimezone('Asia/Tokyo');

        # 文字コード変換
        mb_convert_variables('SJIS-win', 'UTF-8', $attendallname);
        mb_convert_variables('SJIS-win', 'UTF-8', $attendallid);

        header('Content-Type: application/octet-stream');
        header("Content-Disposition: attachment; filename={$title}_出席者");

        $stream = fopen('php://output', 'w');

        // 時刻付与
        $t1 = $time->year;
        $t2 = $time->month;
        $t3 = $time->day;
        $t4 = $time->hour;
        $t5 = $time->minute;
        $now = $t1 . "年" . $t2 . "月" . $t3 . "日" . $t4 . "時" . $t5 . "分";

        // 出席者数
        $num = $syuseki_num . "/" . $risyu_num . " 人";

        mb_convert_variables('SJIS-win', 'UTF-8', $num);
        mb_convert_variables('SJIS-win', 'UTF-8', $title);
        mb_convert_variables('SJIS-win', 'UTF-8', $now);
        $data = [$title, $now];
        fputcsv($stream, $data);

        // 改行させる
        $data = [""];
        fputcsv($stream, $num, $data);

        // 出席番号,学生番号,名前 で格納していく
        for ($i = 0; $i < count($attendallname); $i++) {
            $data = [$i + 1, $attendallid[$i], $attendallname[$i]];
            fputcsv($stream, $data);
        }
    }

    /**
     * サブネットマスクを取得
     * @param string $ip IPアドレス
     * @param int $mask ビット数
     * @return int サブネットマスク
     */
    private function get_subnet_mask(string $ip, int $mask)
    {
        return ip2long($ip) >> (32 - $mask);
    }

    /**
     * 大学内からのアクセスかどうかを判定
     * @param string $ip 判定対象のIPアドレス
     * @param array $ips_list サブネットマスク・IPアドレス・ホスト名のリスト
     * @return bool 大学内からのアクセスかどうか
     */
    private function check_ip(string $ip, array $ips_list)
    {
        // 大学内からのアクセスかどうか
        $in_university = true;

        // 接続元の端末のホスト名
        $access_host = gethostbyaddr($ip);

        if ($access_host == $ip) {
            $access_host = false;
        }

        foreach ($ips_list as $kind => $ips) {
            foreach ($ips as $ip_mask) {
                $ip_mask_ = explode("/", $ip_mask);

                if (count($ip_mask_) == 2) { // サブネットマスクを指定したとき
                    $access_ip = $this->get_subnet_mask($ip, $ip_mask_[1]);
                    $compare_ip = $this->get_subnet_mask($ip_mask_[0], $ip_mask_[1]);
                } else { // IPアドレスやホスト名を指定したとき
                    $compare_ip = $ip_mask_[0];

                    if (!preg_match("/[a-zA-Z]/", $compare_ip)) { // IPアドレスを指定したとき
                        $access_ip = $ip;
                    } else if ($access_host) { // ホスト名が指定され、接続元の端末のホスト名がわかるとき
                        $access_ip = $access_host;
                    } else { // ホスト名が指定されたが、接続元の端末のホスト名がわからないとき
                        continue;
                    }
                }

                switch ($kind) {
                    case "allow":
                        $in_university = $in_university && $access_ip == $compare_ip;
                        break;
                    case "deny":
                        $in_university = $in_university && $access_ip != $compare_ip;
                        break;
                    default:
                        break;
                }

                if (!$in_university) {
                    return $in_university;
                }
            }
        }

        return $in_university;
    }

    // 学生が出席をクリックしたとき
    public function clickUser(Request $request, $lecture)
    {
        $user = Auth::user();
        $ip = getenv('HTTP_X_FORWARDED_FOR');

        if (!$ip) {
            $ip = getenv('REMOTE_ADDR');
        }

        $ips_list = array();

        foreach (["allow", "deny"] as $kind) {
            $ips = array_filter(explode(" ", Config::get("app.${kind}_ips")), 'strlen');

            if (!empty($ips)) {
                $ips_list[$kind] = $ips;
            }
        }

        if (self::check_ip($ip, $ips_list)) {
            // 授業のパスワードを取得
            $pass = \DB::table('lectures')->where('id', $lecture)->value('lecpass');

            // 授業のパスワードと学生が入力したパスワードが一致しているか
            if ($pass == $request->userpass || $pass == '000') {
                // 授業の出席者の学生番号を配列で取得
                $s = \DB::table('lecture_students')->where('lid', $lecture)->pluck('sid')->toArray();

                // 出席クリックした学生が既に出席者配列に含まれていたとき
                if (in_array($user->student_id, (array)$s)) {
                    return redirect('/user')->with('my_status_2', __('出席済みです。'));
                } else {
                    // 出席者配列に新しく追加
                    \DB::table('lecture_students')->insert([
                        'lid' => $lecture,
                        'sname' => $user->name,
                        'sid' => $user->student_id,
                    ]);
                }

                return redirect('/user')->with('my_status', __('出席完了'));
            } else {
                return redirect('/user')->with('my_status_2', __('パスワードが違います。'));
            }

        }

        return redirect('/user')->with('my_status_2', __('教室内から出席してください。'));
    }
}

