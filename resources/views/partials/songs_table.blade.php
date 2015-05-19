
<?php if ($songs->count()): ?>

<table class="table table-striped table-hover table-responsive">
  <thead class="hidden-sm hidden-xs">
    <tr>
      <th>
        <div class="row">
      <div class="col-md-4">Song</div>
      <div class="col-md-2">Artist</div>
      <div class="col-md-2">Album</div>
      <div class="col-md-2">&nbsp;</div>
        </div>
      </th>
    </tr>
  </thead>
  <tbody>
<?php foreach ($songs as $song): ?>
    <tr>
        <td>
          <div class="row">
            <div class="col-md-4">
              <span draggable="true" class="draggable-song" id="song-{{$song->id}}">{{$song->name}}</span>
            </div>
            <div class="col-md-2">
              <a href="/artists/{{$song->artist->id}}">{{$song->artist->name}}</a>
            </div>
            <div class="col-md-2">
              <a href="/albums/{{$song->album->id}}">{{$song->album->name}}</a>
            </div>
            <div class="col-md-4">
            <br>
              <button class="btn btn-success btn-block play-button hidden-md hidden-lg" data-song="{{$song->name}}"
                data-song-url="{!! urlencode($song->filename) !!}"
                data-song-title="{{$song->name}}"
                data-song-artist="{{$song->artist->name}}"
                data-song-album="{{$song->album->name}}">PLAY</button>
              <i class="fa fa-play play-button visible-md visible-lg" data-song="{{$song->name}}"
                data-song-url="{!! urlencode($song->filename) !!}"
                data-song-title="{{$song->name}}"
                data-song-artist="{{$song->artist->name}}"
                data-song-album="{{$song->album->name}}"></i>&nbsp;

<div class="btn-group hidden-sm hidden-xs">
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
