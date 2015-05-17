@extends('app')

@section('content')

<div class="page-header">
<h2 class="page-title">All Songs</h2>
</div>
@include('partials.songs_table', ['songs' => $songs])

@endsection
