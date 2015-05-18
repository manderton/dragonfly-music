<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>{{\Config::get('dragonfly.sitename')}}</title>

	<link href="{{ asset('/css/app.css') }}" rel="stylesheet">
	<link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body>
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/music">{{\Config::get('dragonfly.sitename')}}</a>
			</div>

			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><a href="{{ url('/music') }}">Home</a></li>
				</ul>

				<ul class="nav navbar-nav navbar-right">
					@if (Auth::guest())
						<li><a href="{{ url('/auth/login') }}">Login</a></li>
						<li><a href="{{ url('/auth/register') }}">Register</a></li>
					@else
						<li class="dropdown">
							<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">{{ Auth::user()->name }} <span class="caret"></span></a>
							<ul class="dropdown-menu" role="menu">
								<li><a href="{{ url('/auth/logout') }}">Logout</a></li>
							</ul>
						</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>

	<div class="container">
	<div style="position: fixed;">
@include('partials.player', ['songs' => isset($songs) ? $songs : null])
	</div>

<div class="row">
  <div class="col-md-3">
@include('partials.sidebar')
  </div>
  <div class="col-md-9">
	@yield('content')
  </div>
</div>

	</div>

@include('partials.playlist_modal')

	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="/js/all.js"></script>
	<script>
	var dragSong = null;
	$( function() {
		getPlaylists();

	    $('.play-button').click( function(e) {
	        e.preventDefault();
	        document.getElementById('player').pause()
	        $('.play-button').removeClass('fa-volume-up').addClass('fa-play');
	        $('#player').attr('src', "{{\Config::get('dragonfly.s3_base')}}" + $(this).data('song-url'));
	        document.getElementById('player').play()
	        $('#song').text($(this).data('song'));
	        $('#now-playing').show();
	        notifyNewSong({ title: $(this).data('song-title'), artist: $(this).data('song-artist'), album: $(this).data('song-album') })
	        $(this).removeClass('fa-play').addClass('fa-volume-up');
	    });
	    $('#create-playlist').click( function(e) {
	    	e.preventDefault();
	    	$('#playlist-form-modal').modal();
	    });
	    $("#playlist-form-submit").on("click", function(e) {
		    e.preventDefault();
		    console.log('creating playlist...');
		    $.ajax({
		    	url: "/playlists",
		    	type: 'post',
		    	data: { name: $('#playlist-name').val() },
		    	success: function(data) {
		    		getPlaylists();
		    		$('#playlist-form-modal').modal('hide');
		    	}
		    });
		});
	});
	function applyListeners() {
		console.log('applying listeners...');
		var songs = document.querySelectorAll('.draggable-song');
		[].forEach.call(songs, function(song) {
			song.addEventListener('dragstart', handleDragStart, false);
		});
		var playlists = document.querySelectorAll('.droppable-playlist');
		[].forEach.call(playlists, function(playlist) {
			playlist.addEventListener('dragenter', handleDragEnter, false);
			playlist.addEventListener('dragover', handleDragOver, false);
			playlist.addEventListener('dragleave', handleDragLeave, false);
			playlist.addEventListener('drop', handleDrop, false);
			playlist.addEventListener('dragend', handleDragEnd, false);
		});
	}
	function getPlaylists() {
		$('#playlists-list').html('Loading playlists...');
		console.log('getting playlists...');
		$.get("/playlists", function(data) {
			$('#playlists-list').html('');
			console.log(JSON.stringify(data));
			$.each(data.playlists, function(k, v) {
				console.log(v.name);
				$('#playlists-list').append("<li class='list-group-item droppable-playlist' id='playlist-" + k + "'><span class='badge'>" + v.count + "</span><a href='/playlists/" + k + "'>" + v.name + "</a></li>");
			});
			applyListeners();
		});
	}
	function handleDragLeave(e) {
		e.preventDefault();
		console.log('drag leave...');
		this.classList.remove('drag-over');
	}
	function handleDrop(e) {
		e.preventDefault();
		console.log('dropped...');
		var song = dragSong.id;
		var playlist = this.id;
		console.log('adding song: ' + song + ' to playlist: ' + playlist);
		$.ajax({
			url: "/playlists/add_song/" + playlist + "/" + song,
			type: 'post',
			success: function(data) {
				alert('song added!');
				getPlaylists();
			}
		});
	}
	function handleDragEnd(e) {
		e.preventDefault();
		console.log('drag end...');
	}
	function handleDragStart(e) {
		console.log('dragging song: ' + this.id);
		dragSong = this;
	}
	function handleDragOver(e) {
		e.preventDefault();
		console.log('dragged over...');
		e.dataTransfer.dropEffect = 'move';
		return false;
	}
	function handleDragEnter(e) {
		console.log('drag enter....');
		this.classList.add('drag-over');
	}
	@yield('scripts')
	</script>
</body>
</html>
