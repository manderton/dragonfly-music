@extends('app')

@section('content')

<div class="page-header">
<h2 class="page-title">All Albums</h2>
</div>

@include('partials.albums_table', ['albums' => $albums])

@endsection
