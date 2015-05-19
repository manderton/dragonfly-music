<?php
namespace Dragonfly;

use PhpId3\Id3TagsReader;

class Music {

    /**
     * reads id3 tags from a song file and returns song, artist, album and track info
     * @var localpath full path of the track to analyze
     * @return array
     */
    public static function readTags($localpath)
    {
        \Log::info('reading tags for : ' . $localpath);

        $id3 = new Id3TagsReader(fopen($localpath, "rb"));
        $id3->readAllTags();
        $tags = $id3->getId3Array();

        \Log::info(var_export($tags, true));
        $tagdata = [];
        foreach($id3->getId3Array() as $key => $value) {
            switch ($key) {
                case 'TIT2':
                    $tagdata['song'] = self::cleanString($value['body']);
                    break;
                case 'TPE1':
                    $tagdata['artist'] = self::cleanString($value['body']);
                    break;
                case 'TALB':
                    $tagdata['album'] = self::cleanString($value['body']);
                    break;
                case 'TRCK':
                    $tagdata['track_number'] = self::getTrack($value['body']);
                    break;
                default:
                    break;
            }
        }
        \Log::info(var_export($tagdata, true));
        \Log::info('song: ' . $tagdata['song']);

        return $tagdata;
    }

    /**
     *  TRCK
     * The 'Track number/Position in set' frame is a numeric string
     * containing the order number of the audio-file on its original
     * recording. This MAY be extended with a "/" character and a numeric
     * string containing the total number of tracks/elements on the original
     * recording. E.g. "4/9".
     */
    public static function getTrack($string)
    {
        $parts = explode("/", $string);
        return $parts[0];
    }

    public static function cleanString($string)
    {
        $letters = [' ','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z'];
        $numbers = ['1','2','3','4','5','6','7','8','9','0'];
        $specials = ['.','-',',','$','@','#','%','&','^','!','*','(',')','/',':',';','+'];

        $new_string = "";
        foreach (str_split($string) as $char) {
            if (in_array(strtoupper($char), $letters) || in_array($char, $numbers)) {
                $new_string .= $char;
            }
        }
        return trim($new_string);
    }

    public static function writeArtist($artist, $user_id)
    {
        \Log::info('about to write artist: ' . $artist);
        $db_artist = Artist::where('user_id', $user_id)->where('name', $artist)->first();
        if (!$db_artist) {
            \Log::info('artist does not exist... creating...');
            $db_artist = Artist::create([
                'user_id' => \Auth::user()->id,
                'name' => $artist,
            ]);
        }
        \Log::info('returning artist id: ' . $db_artist->id);
        return $db_artist->id;
    }

    public static function writeAlbum($album, $artist_id, $user_id)
    {
        \Log::info('writing album to db...');
        $db_album = Album::where('user_id', $user_id)
                        ->where('name', $album)
                        ->where('artist_id', $artist_id)
                        ->first();
        if (!$db_album) {
            $db_album = Album::create([
                'user_id'   => $user_id,
                'artist_id' => $artist_id,
                'name'      => $album,
            ]);
        }
        \Log::info('returning album id: ' . $db_album->id);
        return $db_album->id;
    }

    public static function writeSong($data, $user_id, $filename, $artist_id, $album_id)
    {
        $db_song = Song::where('user_id', $user_id)
                        ->where('name', $data['song'])
                        ->where('artist_id', $artist_id)
                        ->where('album_id', $album_id)
                        ->first();
        if (!$db_song) {
            $db_song = song::create([
                'user_id'       => $user_id,
                'album_id'      => $album_id,
                'artist_id'     => $artist_id,
                'name'          => $data['song'],
                'track_number'  => $data['track_number'],
                'filename'      => $filename,
            ]);
        }
        \Log::info('returning song id: ' . $db_song->id);
        return $db_song->id;
    }
}
