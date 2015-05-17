@extends('app')

@section('content')

<div class="page-header">
<h2 class="page-title">Artist - {{$artist->name}}</h2>
</div>

<h4><a href="/artists/songs/{{$artist->id}}">Songs</a></h4>
@include('partials.songs_table', ['songs' => $songs])

<hr>

<h4>Albums</h4>
@include('partials.albums_table', ['albums' => $albums])

@endsection
