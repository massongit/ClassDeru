@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">新規登録</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">氏名</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" placeholder="(例) 鈴木一郎"name="name" value="{{ old('name') }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="student_id" class="col-md-4 col-form-label text-md-right">学生番号</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('student_id') ? ' is-invalid' : '' }}" name="student_id" value="{{ old('student_id') }}" required autofocus>

                                教員の場合、teacherと入力してください。

                                @if ($errors->has('student_id'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('student_id') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                         <div class="form-group row">
                            <label for="univ" class="col-md-4 col-form-label text-md-right">所属大学名</label>

                            <div class="col-md-6">
                                <input id="univ" type="text" class="form-control{{ $errors->has('univ') ? ' is-invalid' : '' }}" name="univ" value="{{ old('univ') }}" required autofocus>

                                @if ($errors->has('univ'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('univ') }}</strong>
                                    </span>
                                @endif
                            </div>
                            大学
                        </div>

                        <div class="form-group row">
                            <label for="gra" class="col-md-4 col-form-label text-md-right">学部</label>

                            <div class="col-md-6">
                                <input id="gra" type="text" class="form-control{{ $errors->has('gra') ? ' is-invalid' : '' }}" name="gra" value="{{ old('gra') }}" required autofocus>

                                @if ($errors->has('gra'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('gra') }}</strong>
                                    </span>
                                @endif
                            </div>
                            学部
                        </div>

                        <div class="form-group row">
                            <label for="dep" class="col-md-4 col-form-label text-md-right">学科</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('dep') ? ' is-invalid' : '' }}" name="dep" value="{{ old('dep') }}" required autofocus>

                                @if ($errors->has('dep'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('dep') }}</strong>
                                    </span>
                                @endif
                            </div>
                            学科
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">メールアドレス</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">パスワード</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">パスワード(確認)</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    登録
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
