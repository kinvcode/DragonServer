<?php

namespace App\Admin\Repositories;

use App\Models\Dungeon as Model;
use Dcat\Admin\Repositories\EloquentRepository;

class Dungeon extends EloquentRepository
{
    /**
     * Model.
     *
     * @var string
     */
    protected $eloquentClass = Model::class;
}
