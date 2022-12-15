<?php

namespace App\Admin\Repositories;

use App\Models\Job as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Job extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
