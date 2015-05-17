@extends('app')

@section('content')

<div class="page-header">
  <div class="row">
    <div class="col-md-2">
      <img src="http://placehold.it/150x150&text=Album+Art" class="img-thumbnail img-responsive">
    </div>
    <div class="col-md-10">
<h2 class="page-title"><?php echo $album->name; ?><br><small><a href="/artists/{{$album->id}}">{{$album->artist->name}}</a></small></h2>
    </div>
  </div>
</div>


<table class="table table-striped table-hover table-responsive">
<?php foreach ($songs as $song): ?>
    <tr>
        <td>{{$song->name}}</td>
        <td><i class="fa fa-play play-button" data-song="{{$song->name}}" data-song-url="{!! urlencode($song->filename) !!}"></i></td>
    </tr>
<?php endforeach; ?>
</table>

@endsection
