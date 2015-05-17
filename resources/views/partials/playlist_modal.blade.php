
<div class="modal fade" id="playlist-form-modal">
  <div class="modal-dialog">
    <div class="modal-content">

<form action="/playlists" method="post" id="playlist-form">

      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Create a Playlist</h4>
      </div>
      <div class="modal-body">


        <div class="form-group">
        <label for="playlist-name">Name</label>
        <input type="text" class="form-control" name="name" id="playlist-name">
        </div>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" id="playlist-form-submit">Create Playlist</button>
      </div>

</form>

    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
