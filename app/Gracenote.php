<?php
namespace Dragonfly;

use Gracenote\Service\Gracenote as GnAPI;
use Dragonfly\UserSetting;

class Gracenote {

    protected $required_settings = ['GRACENOTE_CLIENTID','GRACENOTE_CLIENTTAG','GRACENOTE_USERID'];
    protected $gn_settings;

    public function __construct($user_id)
    {
        // look for Gracenote credentials, bail if there are none...
        $settings = UserSetting::where('key', 'like', 'gracenote_%')
                                ->where('user_id', $user_id)
                                ->get();

        $has_required_settings = [];
        foreach ($settings as $setting) {
            if (in_array($setting->key, $this->required_settings) && !empty($setting->value)) {
                $has_required_settings[] = $setting->key;
                $this->gnsettings[$setting->key] = $setting->value;
            }
        }
        if (empty($diff = array_diff($this->required_settings, $has_required_settings))) {
            \Log::info('required Gracenote settings are ok...');
            return true;
        }
        \Log::info('missing some required Gracenote settings...');
        \Log::info(var_export($diff, true));
        return false;
    }

    public function track_search($song, $album, $artist)
    {
        GnAPI::configure($this->gnsettings['GRACENOTE_CLIENTID'], $this->gnsettings['GRACENOTE_USERID']);
        $simpleXmlResult = GnAPI::query('ALBUM_SEARCH', array(
            'mode' => 'SINGLE_BEST_COVER',
            'parameters' => array(
                'ARTIST'        => $artist,
                'ALBUM_TITLE'   => $album,
                'TRACK_TITLE'   => $song,
            ),
            'options' => array(
                'SELECT_EXTENDED'   => 'COVER,REVIEW,ARTIST_BIOGRAPHY,ARTIST_IMAGE',
                'SELECT_DETAIL'     => 'ARTIST_ORIGIN:4LEVEL,ARTIST_ERA:2LEVEL,ARTIST_TYPE:2LEVEL',
            ),
        ));
        return json_decode(json_encode($simpleXmlResult), true);
    }
}
