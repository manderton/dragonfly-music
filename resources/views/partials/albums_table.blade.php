
<?php if ($albums->count()): ?>

<table class="table table-striped table-responsive table-hover">
<?php foreach ($albums as $album): ?>
  <tr>
    <td>
      <div class="row">
        <div class="col-md-2"><img src="http://placehold.it/100x100&text=Album+Art"></div>
        <div class="col-md-6"><p class="lead"><a href="/albums/{{$album->id}}">{{$album->name}}</a></p>
                              <p><a href="/artists/{{$album->artist->id}}">{{$album->artist->name}}</a></p>
        </div>
      </div>
    </td>
  </tr>
<?php endforeach; ?>
</table>

<?php else: ?>

<div class="text-center">
<p class="lead">No Albums Found</p>
</div>

<?php endif; ?>
