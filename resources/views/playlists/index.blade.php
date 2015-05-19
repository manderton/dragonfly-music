@extends('app')

@section('scripts')
@endsection

@section('content')

<div class="page-header">
<h2 class="page-title">Manage Playlists</h2>
</div>

<table class="table table-striped table-hover table-responsive">
<?php foreach ($playlists as $playlist): ?>
  <tr>
    <td>
      <div class="row">
        <div class="col-md-10">
        <span class="lead">{{$playlist->name}}</span>
            <div class="visible-sm visible-xs">
                <br>
            </div>
        </div>
        <div class="col-md-2">
        <form action="{!! route('playlists.destroy', $playlist->id) !!}" method="DELETE">
            <button type="submit" class="btn btn-danger btn-sm btn-block">Delete</button>
        </div>
      </div>
    </td>
  </tr>
<?php endforeach; ?>
</table>

@endsection
