<?php

namespace App\Http\Controllers;

use App\Models\AccountJob;
use App\Models\DnfAccount;
use App\Models\DnfRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    // 获取基址列表
    public function baseAddress(): JsonResponse
    {
        $data     = DB::table('base_address')->select(['const_name', 'address'])->get();
        $response = [];
        foreach ($data as $value) {
            $response[$value->const_name] = $value->address;
        }
        return new JsonResponse($response);
    }

    // 更新角色列表
    public function updateRoles($favorite, Request $request)
    {
        $data        = $request->all();
        $dnf_account = 0;
        foreach ($data as $value) {
            if ($dnf_account === 0) {
                $dnf_account = $value['account'] ?? null;
            }
            $role_info = [
                'favorite'    => $favorite,
                'account'     => $value['account'] ?? null,
                'server'      => $value['server'] ?? null,
                'name'        => $value['name'] ?? null,
                'character'   => $value['character'] ?? null,
                'advancement' => $value['advancement'] ?? null,
                'awakening'   => $value['awakening'] ?? null,
                'level'       => $value['level'] ?? null,
                'prestige'    => $value['prestige'] ?? null,
                'position'    => $value['position'] ?? null,
            ];
            Log::info('角色信息：',$role_info);
            $exists    = DB::table('dnf_roles')
                ->where('account', $role_info['account'])
                ->where('server', $role_info['server'])
                ->where('name', $role_info['name'])
                ->exists();
            if ($exists) {
                DnfRole::where('name', $role_info['name'])->update($role_info);
            } else {
                DnfRole::create($role_info);
            }
        }

        if ($dnf_account !== null) {
            $exists = DB::table('dnf_account')->where('qq', $dnf_account)->exists();
            if (!$exists) {
                DnfAccount::create(['qq' => $dnf_account]);
            }
        }

    }

    // 更新普通栏角色列表
    public function updateGeneralRoles(Request $request)
    {
        $this->updateRoles(0, $request);
    }

    // 更新偏爱栏角色列表
    public function updateFavoriteRoles(Request $request)
    {
        $this->updateRoles(1, $request);
    }

    // 获取城镇坐标列表
    public function townCoordinates(): JsonResponse
    {
        $data = DB::table('town_coordinates')->select(['name', 'word', 'area', 'x', 'y'])->get();
        return new JsonResponse($data);
    }

    // 获取任务队列
    public function jobs($id,Request $request)
    {
        $server = $request->input('server');
        $data = $request->all();
        Log::info('请求任务：',$data);
        $dnf_date = date('Y-m-d', strtotime('-6 hours'));
        $data     = DB::table('account_jobs')
            ->where('account', $id)
            ->where('server', $server)
            ->where('job_date', $dnf_date)
            ->first();
        if ($data !== null) {
            return new JsonResponse(json_decode($data->raw, true));
        }

        $data = [];
        $jobs = DB::table('jobs')->orderBy('sort', 'desc')->get();

        if ($jobs->count() < 1) {
            return new JsonResponse($data);
        }

        // 获取账号下所有角色[一个大区]
        $roles    = DB::table('dnf_roles')
            ->where('account', $id)
            ->where('server',$server)
            ->orderBy('position')
            ->get();
        $roles_id = $roles->pluck('id');
        if ($roles->isEmpty()) {
            // 如果没有角色
            return new JsonResponse($data);
        }

        $jobs = DB::table('jobs')->whereIn('role_id', $roles_id)->get();
        if ($jobs->isEmpty()) {
            // 如果没有任务
            return new JsonResponse($data);
        }
        // 获取策略
        $strategies   = DB::table('job_strategies')->whereIn('id', $jobs->pluck('strategy_id'))->get();
        $strategy_map = [];
        foreach ($strategies as $value) {
            $strategy_map[$value->id] = $value;
        }

        $jobs_map = [];
        foreach ($jobs as $value) {
            // 策略
            $strategy = [
                'type' => $strategy_map[$value->strategy_id]->type,
                'data' => json_decode($strategy_map[$value->strategy_id]->raw)
            ];

            $jobs_map[$value->role_id][$value->id] = $strategy;
        }

        // 任务排序
        foreach ($jobs_map as $key => $value) {
            ksort($value);
            $jobs_map[$key] = [];
            foreach ($value as $val) {
                $jobs_map[$key][] = $val;
            }
        }
        // 合并结果
        $response = [];
        foreach ($roles as $value) {
            if (isset($jobs_map[$value->id])) {
                $jobs       = $jobs_map[$value->id];
                $response[] = [
                    'jobs' => $jobs,
                    'role' => [
                        'pos'      => $value->position,
                        'favorite' => $value->favorite
                    ]
                ];
            }
        }
        AccountJob::create(['account' => $id, 'job_date' => $dnf_date, 'raw' => json_encode($response)]);
        return new JsonResponse($response);
    }

    // 更新角色ID
    public function updateRoleID(Request $request)
    {
        $data = $request->all();

        if (!isset($data['account']) || !isset($data['name']) || !isset($data['id'])) {
            return;
        }

        $role_id = DB::table('dnf_roles')->where('account', $data['account'])->where('name', $data['name'])->value('role_id');
//        if (empty($role_id)) {
        DB::table('dnf_roles')->where('account', $data['account'])->where('name', $data['name'])->update(['role_id' => $data['id']]);
//        }

    }

    // 更新账号任务
    public function updateAccountJob($id, Request $request)
    {
        $type = (int)$request->input('type');
        if (!in_array($type, [0, 1, 2])) {
            abort(400);
        }

        $dnf_date = date('Y-m-d', strtotime('-6 hours'));
        $jobs     = DB::table('account_jobs')->where('account', $id)->where('job_date', $dnf_date)->first();
        $jobs     = json_decode($jobs->raw, true);
        if (isset($jobs[0])) {
            $role_jobs = &$jobs[0]["jobs"];
            if (isset($role_jobs[0])) {
                $cur_job = &$role_jobs[0];
                if ($cur_job["type"] === $type) {
                    switch ($type) {
                        case 0:
                            $data = &$cur_job["data"];
                            if (count($data) > 0) {
                                $data[0]["times"] -= 1;
                                if ($data[0]["times"] == 0 || $data[0]["times"] == -1) {
                                    array_shift($data);
                                    if (count($data) < 1) {
                                        array_shift($role_jobs);
                                        if (count($role_jobs) < 1) {
                                            array_shift($jobs);
                                        }
                                    }
                                }
                            }
                            break;
                        case 1:
                        case 2:
//                            Log::info('当前任务已完成',['job_type'=>$type]);
                            array_shift($role_jobs);
                            if (count($role_jobs) < 1) {
                                array_shift($jobs);
                            }
                            break;
                        default:
                            abort(400);
                    }
                }
            }
        }

        try {
            DB::table('account_jobs')
                ->where('account', $id)
                ->where('job_date', $dnf_date)
                ->update(['raw' => json_encode($jobs)]);
        } catch (\Exception $e) {
            abort(500);
        }
    }
}
