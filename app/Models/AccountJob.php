<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class AccountJob extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'account_jobs';
    protected $fillable = ['account', 'raw', 'job_date'];

}
