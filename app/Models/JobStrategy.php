<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class JobStrategy extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'job_strategies';
    
}
