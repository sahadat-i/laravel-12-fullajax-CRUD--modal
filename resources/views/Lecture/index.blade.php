@extends('layouts.app')

@section('content')
<h1>Welcome to the Lecture Page</h1>
<table class="table">
  <thead>
    <tr>
      <th scope="col">#</th>
      <th scope="col">Lecture Name</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($datas as $data)
    <tr>
      <th scope="row">{{ $data->id }}</th>
      <td>{{ $data->lecture_name }}</td>
    </tr>
    @endforeach
  </tbody>
</table>
<div class="d-flex">
  {!! $datas->links() !!}
</div>
@endsection