<?php namespace Dragonfly;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model {

	protected $fillable = ['user_id', 'key', 'value'];

}
