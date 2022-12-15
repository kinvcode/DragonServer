<?php

namespace App\Admin\Repositories;

use App\Models\JobStrategy as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class JobStrategy extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
