@extends('app')

@section('content')

<div class="page-header">
<h2 class="page-title">Artists</h2>
</div>

<?php if ($artists->count()): ?>

<div class="row">
<?php $counter = 0; ?>
<?php foreach ($artists as $artist): ?>
  <div class="col-md-4">
    <div class="thumbnail text-center">
      <p class="lead"><a href="/music/artist/<?php echo $artist->id; ?>"><?php echo $artist->name; ?></a></p>
    </div>
  </div>
  <?php
  $counter++;
  if ($counter % 3 == 0):
  ?>
</div>
<div class="row">
  <?php endif; ?>
<?php endforeach; ?>
</div>

<?php else: ?>

<div class="text-center">
<p class="lead">No Artists Found</p>
</div>

<?php endif; ?>

@endsection
