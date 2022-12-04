<?php

namespace App\Admin\Repositories;

use App\Models\DnfRole as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class DnfRole extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
