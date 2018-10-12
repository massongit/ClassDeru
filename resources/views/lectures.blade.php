@extends('layouts.app')

<!-- ログインしている教員側に表示する授業登録画面 -->
@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					新規授業
				</div>

				<div class="panel-body">
					<!-- エラーの確認 -->
					@include('common.errors')

					<!-- 新規授業登録フォーム -->
					<form action="/lecture" method="POST" class="form-horizontal">
						{{ csrf_field() }}

						<!-- 追加する授業の情報 -->
						<div class="form-group">
							<label for="task-name" class="col-sm-3 control-label">授業名</label>

							<div class="col-sm-6">
								<input type="text" name="title" placeholder="(例) 数学解析"id="title" class="form-control" value="{{ old('title') }}">
							</div>


							<label for="task-name" class="col-sm-3 control-label">開講する大学</label>

							<div class="col-sm-6">
								<input type="text" name="univ" placeholder="(例) 茨城" id="univ" class="form-control" value="{{ old('univ') }}">
							</div>

							<label for="task-name" class="col-sm-3 control-label">開講する学部</label>

							<div class="col-sm-6">
								<input type="text" name="gra" id="gra" class="form-control" placeholder="(例) 工"value="{{ old('gra') }}">
							</div>

							<label for="task-name" class="col-sm-3 control-label">開講する学科</label>

							<div class="col-sm-6">
								<input type="text" name="dep" id="dep" class="form-control" placeholder="(例) 情報工" value="{{ old('dep') }}">
							</div>


							<label for="task-name" class="col-sm-3 control-label">全受講者数</label>

							<div class="col-sm-6">
								<input type="text" name="number" placeholder="(例) 100" id="number" class="form-control" value="{{ old('number') }}">
							</div>


							<label for="task-name" class="col-sm-3 control-label">開講日時</label>

							<div class="col-sm-6">
								<input type="text" name="date" id="date" class="form-control" value="{{ old('date') }}">
							</div>
						</div>


						<!-- 授業を追加 -->
						<div class="form-group">
							<div class="col-sm-offset-3 col-sm-6">
								<button type="submit" class="btn btn-default">
									<i class="fa fa-plus"></i>授業を追加する
								</button>
							</div>
						</div>
					</form>
				</div>
			</div>

			<!-- 授業一覧 -->
			@if (count($lectures) > 0)
				<div class="panel panel-default">
					<div class="panel-heading">
						授業一覧
					</div>

					<div class="panel-body">
						<table class="table table-striped task-table">
							<thead>
								<th>授業名</th>
								<th>出席者数 / 全履修数</th>
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

										<!-- 出席者確認ボタン -->
										<td>
											<form action="/lecture/{{ $lecture->id}}/kekka" method="GET">
												
												<button type="submit" class="btn btn-info" value="{{Auth::user()->student_id}}">確認
												</button>
											</form>
										</td>

										<!-- 削除ボタン -->
										<td>
											<form action="/lecture/{{ $lecture->id }}" method="POST">
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

								<!-- 出席ボタン -->
								<td>
									<form action="/lecture/{{ $lecture->id }}" method="POST">
										{{ csrf_field() }}

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
	@endif
@endsection


