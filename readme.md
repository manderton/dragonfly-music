# dragonfly-music
Dragonfly Music Streamer

Dragonfly is an attempt to clone Google Play Music or Amazon's Cloud Player for those
irked by their 10-device limit.


### INSTALLATION

#### Requirements
+ PHP 5.4 or higher
+ an AWS account
+ MySQL

#### Steps
+ clone the app
+ create an AWS account and an S3 bucket
+ create an AWS IAM user with S3 full access
+ add IAM credentials to .env file (copy from .env.example)


### TODO (in no particular order)
+ desktop notifications - "Now Playing", etc.
+ allow downloading of artists, albums, playlists, etc.
+ 'next' and 'previous' buttons for lists of songs
+ need an Angular or React single-page app interface so that music continues to play as you move around the app
+ need to fix multi-file upload
+ fix file permissions on AWS upload so not readable by the world
+ create a 'Refresh Library' function that will nuke users's songs, artists and albums and rescan S3
    + this will also need to refresh playlists and get rid of files that were changed/removed
