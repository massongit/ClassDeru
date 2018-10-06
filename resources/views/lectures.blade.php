@extends('layouts.app')

@section('content')
	<div class="container">
		<div class="col-sm-offset-2 col-sm-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					新規授業
				</div>

				<div class="panel-body">
					<!-- Display Validation Errors -->
					@include('common.errors')

					<!-- New Lecture Form -->
					<form action="/lecture" method="POST" class="form-horizontal">
						{{ csrf_field() }}

						<!-- Lecture title -->
						<div class="form-group">
							<label for="task-name" class="col-sm-3 control-label">授業名</label>

							<div class="col-sm-6">
								<input type="text" name="title" id="lecture-title" class="form-control" value="{{ old('class') }}">
							</div>
						</div>

						<!-- Add Lecture Button -->
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

			<!-- Lectures -->
			@if (count($lectures) > 0)
				<div class="panel panel-default">
					<div class="panel-heading">
						授業一覧
					</div>

					<div class="panel-body">
						<table class="table table-striped task-table">
							<thead>
								<th>Lecture</th>
								<th>&nbsp;</th>
							</thead>
							<tbody>
								@foreach ($lectures as $lecture)
									<tr>
										<td class="table-text"><div>{{ $lecture->title }}</div></td>

										<!-- Task Delete Button -->
										<td>
											<form action="/lecture/{{ $lecture->id }}" method="POST">
												{{ csrf_field() }}
												{{ method_field('DELETE') }}

												<button type="submit" class="btn btn-danger">
													<i class="fa fa-trash"></i>削除
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


