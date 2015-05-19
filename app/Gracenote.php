<?php
namespace Dragonfly;

use Gracenote\Service\Gracenote;
use Dragonfly\UserSetting;

class Gracenote {

    protected $required_settings = ['GRACENOTE_CLIENTID','GRACENOTE_CLIENTTAG','GRACENOTE_USERID'];

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
}
