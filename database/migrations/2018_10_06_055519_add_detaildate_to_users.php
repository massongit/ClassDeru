<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDetaildateToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function($table) {
            $table->string('univ')->default('ibaraki'); // 所属大学
            $table->string('gra')->default('eng'); // 所属学部
            $table->string('dep')->default('it');  // 所属学科
            $table->string('student_id')->default('1a');; //学生番号
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function($table) {
            $table->dropColumn('univ');
            $table->dropColumn('gra');
            $table->dropColumn('dep');
            $table->dropColumn('student_id');
        });
    }
}
