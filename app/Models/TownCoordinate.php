<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class TownCoordinate extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'town_coordinates';
    
}
