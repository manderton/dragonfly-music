$( function() {
    window.addEventListener('load', function () {
      Notification.requestPermission(function (status) {
        // This lets us use Notification.permission with Chrome/Safari
        if (Notification.permission !== status) {
          Notification.permission = status;
        }
      });
    });

});
function notifyNewSong(songdata) {
    var n = new Notification('Dragonfly - Now Playing', { body: songdata.title + "\nby: " + songdata.artist + "\non: " + songdata.album });
    n.onshow = function () {
      setTimeout(n.close.bind(n), 5000);
    }
}

//# sourceMappingURL=all.js.map