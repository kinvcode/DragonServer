<?php

namespace App\Admin\Repositories;

use Dcat\Admin\Form;
use Dcat\Admin\Repositories\Repository;
use Illuminate\Support\Facades\DB;

class MasterJob extends Repository
{
    public function edit(Form $form)
    {
        $data = DB::table('jobs')->where('type', 0)->orderBy('sort', 'asc')->get()->toArray();

        $result               = [];
        $result['strategies'] = [];
        foreach ($data as $value) {
            $result['strategies'][] = ['job' => $value->strategy_id];
        }
        return $result;
    }
}
