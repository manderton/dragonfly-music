
<div class="well">
    <div class="row">
      <div class="col-md-2">
      <button onclick="document.getElementById('player').play()"><i class="fa fa-play"></i></button>
      <button onclick="document.getElementById('player').pause()"><i class="fa fa-pause"></i></button>
      <button onclick="document.getElementById('player').volume-=0.1"><i class="fa fa-volume-down"></i></button>
      <button onclick="document.getElementById('player').volume+=0.1"><i class="fa fa-volume-up"></i></button>
      </div>
      <div class="col-md-4">
    <audio id="player" controls autoplay>
      <?php if ($songs): ?>
        <?php foreach ($songs as $song): ?>
          <source src="{{\Config::get('dragonfly.s3_base')}}{!! urlencode($song->filename) !!}" type="audio/mp3">
        <?php endforeach; ?>
      <?php endif; ?>
    </audio>
      </div>
      <div class="col-md-6">
    <p class="lead" id="now-playing" style="display: none;">Now Playing: <span id="song"></span></p>
      </div>
    </div>
</div>
