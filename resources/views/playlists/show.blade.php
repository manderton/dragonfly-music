@extends('app')

@section('content')

<div class="page-header">
<h2 class="page-title">Playlist - <?php echo $playlist->name; ?></h2>
</div>

@include('partials.songs_table', ['songs' => $playlist->songs])

@endsection
