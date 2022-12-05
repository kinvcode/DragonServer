<?php

namespace App\Models;

use Dcat\Admin\Traits\HasDateTimeFormatter;

use Illuminate\Database\Eloquent\Model;

class DnfRole extends Model
{
    use HasDateTimeFormatter;

    protected $table = 'dnf_roles';
    protected $fillable = ['favorite', 'account', 'role_id', 'name', 'character', 'advancement', 'awakening', 'level', 'prestige', 'position'];
}
