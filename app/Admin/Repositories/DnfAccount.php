<?php

namespace App\Admin\Repositories;

use App\Models\DnfAccount as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class DnfAccount extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
