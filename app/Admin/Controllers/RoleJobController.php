<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\MasterJob;
use App\Admin\Repositories\RoleJob;
use App\Http\Controllers\Controller;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Widgets\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleJobController extends Controller
{
    public function editJob($role_id, Content $content)
    {
        if (empty($role_id)) {
            admin_toastr('角色ID不存在', 'warning');
            return admin_redirect(admin_url('roles'));
        }

        $form = Form::make(RoleJob::class, function (Form $form) {
            $form->disableHeader();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableViewCheck();

            $form->table('strategies', '任务列表', function ($table) {
                $table->select('job', '任务策略')->options('/options/job');
            })->saving(function ($v) {
                return json_encode($v);
            });
        });

        $form->action('role/' . $role_id . '/job');

        $card = new Card("任务执行顺序按照以下任务排列进行");
        return $content
            ->title('角色任务配置')
            ->description()
            ->body($card)
            ->body($form->edit($role_id));
    }

    public function updateJob($role_id, Request $request)
    {
        if (empty($role_id)) {
            admin_toastr('角色ID不存在', 'warning');
            return admin_redirect(admin_url('roles'));
        }

        $jobs = (array)$request->input('strategies');
        foreach ($jobs as $key => $value) {
            if ($value['_remove_'] == '1') {
                unset($jobs[$key]);
            }
        }

        if (count($jobs) < 1) {
            DB::table('jobs')->where('type', 1)->where('role_id', $role_id)->delete();
            $account = DB::table('dnf_roles')->where('role_id', $role_id)->value('account');
            $url     = '/account/' . $account;
            return Response::make()->success('任务已清空')->location($url);
        }

        $account = DB::table('dnf_roles')->where('role_id', $role_id)->first()->account;

        $date      = date('Y-m-d H:i:s');
        $save_data = [];
        $index     = 0;
        foreach ($jobs as $value) {
            if (empty($value['job'])) {
                return Response::make()->info('任务策略不能为空');
            }
            $save_data[] = [
                'strategy_id'  => $value['job'],
                'sort'         => $index,
                'type'         => 1,
                'role_id'      => $role_id,
                'role_account' => $account,
                'created_at'   => $date,
                'updated_at'   => $date,
            ];
            $index++;
        }

        try {
            $dnf_date = date('Y-m-d', strtotime('-6 hours'));
            DB::table('account_jobs')->where('account', $account)->where('job_date', $dnf_date)->delete();
            DB::table('jobs')->where('type', 1)->where('role_id', $role_id)->delete();
            DB::table('jobs')->insert($save_data);
        } catch (\Exception $e) {
            return Response::make()->error('创建失败！');
        }

        $account = DB::table('dnf_roles')->where('role_id', $role_id)->value('account');
        $url     = '/account/' . $account;
        return Response::make()->success('创建成功')->location($url);
    }
}
