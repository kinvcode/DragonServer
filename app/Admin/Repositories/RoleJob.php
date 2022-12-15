<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Form;
use Dcat\Admin\Repositories\Repository;
use Illuminate\Support\Facades\DB;

class RoleJob extends Repository
{
    public function edit(Form $form)
    {
        $id   = $form->builder()->getResourceId();
        $data = DB::table('jobs')->where('type', 1)->where('role_id', $id)->orderBy('sort', 'asc')->get()->toArray();

        $result               = [];
        $result['strategies'] = [];
        foreach ($data as $value) {
            $result['strategies'][] = ['job' => $value->strategy_id];
        }
        return $result;
    }
}
