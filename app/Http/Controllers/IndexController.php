<?php

namespace App\Http\Controllers;

use App\Models\DnfRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    public function baseAddress()
    {
        $data = DB::table('base_address')->select(['const_name', 'address'])->get();
        return new JsonResponse($data);
    }

    public function updateRoles($favorite, Request $request)
    {
        $data = $request->all();
        foreach ($data as $value) {
            $role_info = [
                'favorite'    => $favorite,
                'account'     => $value['account'] ?? null,
                'role_id'     => $value['role_id'] ?? null,
                'name'        => $value['name'] ?? null,
                'character'   => $value['character'] ?? null,
                'advancement' => $value['advancement'] ?? null,
                'awakening'   => $value['awakening'] ?? null,
                'level'       => $value['level'] ?? null,
                'prestige'    => $value['prestige'] ?? null,
                'position'    => $value['position'] ?? null,
            ];
            $exists    = DB::table('dnf_roles')->where('name', $role_info['name'])->exists();
            if ($exists) {
                DnfRole::where('name', $role_info['name'])->update($role_info);
            } else {
                DnfRole::create($role_info);
            }
        }
    }

    public function updateGeneralRoles(Request $request)
    {
        $this->updateRoles(0, $request);
    }

    public function updateFavoriteRoles(Request $request)
    {
        $this->updateRoles(1, $request);
    }
}
