<?php

namespace App\Admin\Repositories;

use App\Models\BaseAddress as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class BaseAddress extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
