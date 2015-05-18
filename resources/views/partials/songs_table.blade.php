
<?php if ($songs->count()): ?>

<table class="table table-striped table-hover table-responsive">
  <thead>
    <tr>
      <th>Song</th>
      <th>Artist</th>
      <th>Album</th>
      <th>&nbsp;</th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($songs as $song): ?>
    <tr>
        <td><span draggable="true" class="draggable-song" id="song-{{$song->id}}">{{$song->name}}</span></td>
        <td><a href="/artists/{{$song->artist->id}}">{{$song->artist->name}}</a></td>
        <td><a href="/albums/{{$song->album->id}}">{{$song->album->name}}</a></td>
        <td><i class="fa fa-play play-button" data-song="{{$song->name}}"
                data-song-url="{!! urlencode($song->filename) !!}"
                data-song-title="{{$song->name}}"
                data-song-artist="{{$song->artist->name}}"
                data-song-album="{{$song->album->name}}"></i>&nbsp;

<div class="btn-group">
  <button type="button" class="btn btn-default dropdown-toggle btn-xs" data-toggle="dropdown" aria-expanded="false">
    <span class="caret"></span>
  </button>
  <ul class="dropdown-menu" role="menu">
    <li><a href="#">Action</a></li>
    <li><a href="#">Another action</a></li>
    <li><a href="#">Something else here</a></li>
    <li class="divider"></li>
    <li><a href="#">Separated link</a></li>
  </ul>
</div>
        </td>
    </tr>
<?php endforeach; ?>
  </tbody>
</table>

<?php
if (method_exists($songs, 'render')) {
  echo $songs->render();
}
?>

<?php else: ?>

<div class="text-center">
<p class="lead">No Songs Found</p>
</div>
<?php endif; ?>
