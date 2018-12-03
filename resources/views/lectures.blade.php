@extends('layouts.app')

<!-- ログインしている教員側に表示する授業登録画面 -->
@section('content')
<center>
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">

			<div class="box_lec">
        <span class="box-title">新規授業</span>
        <p>
          <form action="/user/lecture" method="POST" class="form-horizontal">
              {{ csrf_field() }}

            <div class="form-group">
              <div class="form-group col-md-4">
                <label for="task-name">授業名</label>
                <input type="text" name="title" placeholder="(例) 数学解析" id="title" class="form-control" value="{{ old('title') }}">
              </div>
            </div>

            <div class="form-row">
              <div class="form-group col-md-4">
                <label for="task-name">開講する大学</label>

                <input type="text" name="univ" placeholder="(例) 茨城" id="univ" class="form-control" value="{{ old('univ') }}">
              </div>
              
              <div class="form-group col-md-4">
                <label for="task-name">学部</label>

                  <input type="text" name="gra" id="gra" class="form-control" placeholder="(例) 工" value="{{ old('gra') }}">
              </div>

              <div class="form-group col-md-4">
                <label for="task-name" class="control-label">学科</label>

                <input type="text" name="dep" id="dep" class="form-control" placeholder="(例) 情報工" value="{{ old('dep') }}">
              </div>
            </div>


            <div class="form-group">
              <div class="col-sm-4">
                <label for="task-name" class="control-label">全受講者数</label>
                <input type="text" name="number" id="number" class="form-control" placeholder="(例) 100" value="{{ old('number') }}">
              </div>
            </div>

            <div class="form-group">
              <div class="col-sm-4">
                <label for="task-name" class="control-label">教室・連絡</label>
                <input type="text" name="date" id="date" class="form-control" value="{{ old('date') }}">
              </div>
            </div>

            <div class="form-group">
               <div class="col-sm-4">
                <label for="task-name" class="control-label">パスワード(未入力可)</label>
                <input type="text" name="lecpass" id="lecpass" class="form-control" value="{{ old('lecpass') }}" autocomplete="off" >
              </div>
            </div>

            <div class="form-group">
				<div class="col-sm-offset-3 col-sm-6">
					<button type="submit" class="btn btn-success">
						授業を追加する
					</button>
				</div>
			</div>
          </form>
        </p>
    </div>

			<!-- 授業一覧 -->
			@if (count($lectures) > 0)
				<div class="panel panel-default">
					<br>

					<div class="panel-body">
						<table class="table table-striped task-table">
							<thead>
								<th>授業名</th>
								<th>出席者数 / 全履修数</th>
								<th>教室・連絡</th>
								<th>パスワード</th>
								<th>&nbsp;</th>
								<th>&nbsp;</th>
							</thead>
							<tbody>
								@foreach ($lectures as $lecture)
									<tr>
										<td class="table-text"><div>{{ $lecture->title }}</div></td>

										<!-- 人数 -->
										<td>
											{{ $lecture->attendCount($lecture) }} / {{ $lecture->number }}
										</td>

										<!-- 連絡事項を表示 -->
										<td>
											{{ $lecture->date }}
										</td>

										<!-- パスワードを表示 -->
										<td>
											{{ $lecture->lecpass }}
										</td>

										<!-- 出席者確認ボタン -->
										<td>
											<form action="/user/lecture/{{ $lecture->id}}/kekka" method="GET">
												
												<button type="submit" class="btn btn-info" value="{{Auth::user()->student_id}}">確認
												</button>
											</form>
										</td>

										<!-- 削除ボタン -->
										<td>
											<form action="/user/lecture/{{ $lecture->id }}" method="POST">
												{{ csrf_field() }}
												{{ method_field('DELETE') }}

												<button type="submit" class="btn btn-danger">
													削除
												</button>
											</form>
										</td>

									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			@endif
		</div>
	</div>
@endsection



<!-- ログインしている学生側に表示する授業一覧 -->
@section('studentlec')
	@if (count($lectures)>0 and count($lecTeachers)>0)

		<div class="panel panel-default">

			<div class="panel-body">
				<table class="table table-striped task-table">
					<thead>
						<th>現在開講している授業</th>
						<th>教員名</th>
						<th>教室・連絡事項</th>
						<th>パスワード</th>
						<th>&nbsp;</th>
					</thead>
					<tbody>
						@foreach (array_map(null, $lectures, $lecTeachers) as [$lecture, $t])
							<tr>
								<td class="table-text"><div>{{ $lecture->title }}</div></td>

								<!-- 授業の教員名を表示 -->
								<td>
									{{ $t }}
								</td>

								<!-- 教室・連絡事項 -->
								<td>
									{{ $lecture->date }}
								</td>

								<!-- パスワード入力フォーム-->
								</td>

								</td>

								<!-- 出席ボタン -->
								<td>
									<form action="/user/lecture/{{ $lecture->id }}" method="POST" onSubmit="attention()">
										
										<div class="col-sm-6" style="display:inline-flex">
											<input type="text" name="userpass" id="userpass" class="form-control" value="" autocomplete="off">
										</div>

										<input type="hidden" name="_token" value="{{csrf_token()}}">
										<button type="submit" class="btn btn-success"　value="">
											出席
										</button>

									</form>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
  </center>
	@else

		<div class="container mt-2">
			<div class="alert alert-info">
				<center>現在&nbsp;{{ $useruniv }}大学&nbsp;で開講されている授業はありません。</center>
			</div>
		</div>

	@endif
@endsection

