<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class DnfAccount extends Model
{
	use HasDateTimeFormatter;
    protected $table = 'dnf_account';
    protected $fillable = ['qq'];
}
