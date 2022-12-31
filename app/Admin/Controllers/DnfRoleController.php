<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\DnfRole;
use App\Admin\Repositories\MasterJob;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DnfRoleController extends AdminController
{
    public $character_map = [
        0  => [
            'name' => '鬼剑士（男）',
            'adv'  => [
                1 => '剑魂',
                2 => '鬼泣',
                3 => '狂战士',
                4 => '阿修罗',
                5 => '剑影',
            ],
        ],
        1  => [
            'name' => '格斗家（女）',
            'adv'  => [
                1 => '气功师',
                2 => '散打',
                3 => '街霸',
                4 => '柔道',
            ],
        ],
        2  => [
            'name' => '神枪手（男）',
            'adv'  => [
                1 => '漫游枪手',
                2 => '枪炮师',
                3 => '机械师',
                4 => '弹药专家',
                5 => '合金战士',
            ],
        ],
        3  => [
            'name' => '魔法师（女）',
            'adv'  => [
                1 => '元素师',
                2 => '召唤师',
                3 => '战斗法师',
                4 => '魔道学者',
                5 => '小魔女',
            ],
        ],
        4  => [
            'name' => '圣职者（男）',
            'adv'  => [
                1 => '圣骑士',
                2 => '蓝拳圣使',
                3 => '驱魔师',
                4 => '复仇者',
            ],
        ],
        5  => [
            'name' => '神枪手（女）',
            'adv'  => [
                1 => '漫游枪手',
                2 => '枪炮师',
                3 => '机械师',
                4 => '弹药专家',
            ],
        ],
        6  => [
            'name' => '暗夜使者',
            'adv'  => [
                1 => '刺客',
                2 => '死灵术士',
                3 => '忍者',
                4 => '影舞者',
            ],
        ],
        7  => [
            'name' => '格斗家（男）',
            'adv'  => [
                1 => '气功师',
                2 => '散打',
                3 => '街霸',
                4 => '柔道家',
            ],
        ],
        8  => [
            'name' => '魔法师（男）',
            'adv'  => [
                1 => '元素爆破师',
                2 => '冰结师',
                3 => '血法师',
                4 => '逐风者',
                5 => '次元行者',
            ],
        ],
        9  => [
            'name' => '缔造者',
            'adv'  => [
                1 => '缔造者',
            ],
        ],
        10 => [
            'name' => '黑暗武士',
            'adv'  => [
                1 => '黑暗武士',
            ],
        ],
        11 => [
            'name' => '鬼剑士（女）',
            'adv'  => [
                1 => '驭剑士',
                2 => '暗殿骑士',
                3 => '契魔者',
                4 => '流浪武士',
                5 => '刃影',
            ],
        ],
        12 => [
            'name' => '守护者',
            'adv'  => [
                1 => '精灵骑士',
                2 => '混沌魔灵',
                3 => '帕拉丁',
                4 => '龙骑士',
            ],
        ],
        13 => [
            'name' => '魔枪士',
            'adv'  => [
                1 => '征战者',
                2 => '决战者',
                3 => '狩猎者',
                4 => '暗枪士',
            ],
        ],
        14 => [
            'name' => '圣职者（女）',
            'adv'  => [
                1 => '圣骑士',
                2 => '异端审判者',
                3 => '巫女',
                4 => '诱魔者',
            ],
        ],
        15 => [
            'name' => '枪剑士',
            'adv'  => [
                1 => '暗刃',
                2 => '特工',
                3 => '战线佣兵',
                4 => '源能专家',
            ],
        ],
    ];

    public $advancement_map = [
        1 => '剑魂',
    ];

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $map1 = $this->character_map;
        return Grid::make(new DnfRole(), function (Grid $grid) use ($map1) {
            $master = <<<STR
<a href="/admin/role/master" class="btn btn-primary">
    <i class="feather icon-edit-2"></i><span class="d-none d-sm-inline">&nbsp;&nbsp;默认任务</span>
</a>
STR;
            $grid->tools($master);
            $grid->toolsWithOutline(false);
            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->disableEditButton();

            $grid->column('id')->sortable();
            $grid->column('account');
            $grid->column('server');
//            $grid->column('role_id')->display(function ($role_id) {
//                return $role_id ?? '未获取';
//            });
            $grid->column('name');
            $grid->column('character')->display(function ($role) use ($map1) {
                if (isset($map1[$role])) {
                    return $map1[$role]['name'];
                }
                return $role;
            });
            $grid->column('advancement')->display(function ($adv) use ($map1) {
                $role = $this->character;
                if (isset($map1[$role]) && isset($map1[$role]['adv'][$adv])) {
                    return $map1[$role]['adv'][$adv];
                } else {
                    return $adv;
                }
            });
            $grid->column('awakening')->display(function ($wake) {
                if ($wake) {
                    return $wake . '次觉醒';
                } else {
                    return '未觉醒';
                }
            });
            $grid->column('level');
            $grid->column('prestige');
            $grid->column('favorite');
            $grid->column('position');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $role_id = (int)$this->role_id;
                // append一个操作
                $actions->append('<a href="/admin/role/'.$role_id.'/job"><i class="feather icon-edit"></i>配置任务</a>');
            });

            $grid->filter(function (Grid\Filter $filter) {
                $filter->expand();
                $filter->panel();
                $filter->equal('account');
                $filter->like('name');
            });
        });
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new DnfRole(), function (Show $show) {
            $show->disableEditButton();
            $show->disableDeleteButton();
            $show->field('id');
            $show->field('account');
            $show->field('role_id');
            $show->field('name');
            $show->field('character');
            $show->field('advancement');
            $show->field('awakening');
            $show->field('level');
            $show->field('prestige');
            $show->field('position');
            $show->field('created_at');
            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new DnfRole(), function (Form $form) {
            $form->display('id');
            $form->text('account');
            $form->text('role_id');
            $form->text('name');
            $form->text('character');
            $form->text('advancement');
            $form->text('awakening');
            $form->text('level');
            $form->text('prestige');
            $form->text('position');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }

    public function masterJob(Content $content)
    {
        $form = Form::make(MasterJob::class, function (Form $form) {
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

        $form->action('role/master');

        $card  = new Card("默认任务的作用仅仅是作为模板使用，建议为每个角色配置独立的任务");
        $card2 = new Card("任务执行顺序按照以下任务排列进行");
        return $content
            ->title('默认任务配置')
            ->description()
            ->body($card)
            ->body($card2)
            ->body($form->edit(null));
    }

    public function jobOptions()
    {
        $data     = DB::table('job_strategies')->select(['id', 'name'])->get();
        $response = [];
        foreach ($data as $value) {
            $response[] = [
                'id'   => $value->id,
                'text' => $value->name
            ];
        }
        return $response;
    }

    public function saveMasterJob(Request $request)
    {
        $jobs = $request->input('strategies');
        foreach ($jobs as $key => $value) {
            if ($value['_remove_'] == '1') {
                unset($jobs[$key]);
            }
        }

        if (count($jobs) < 1) {
            return Response::make()->info('任务策略列表不能为空');
        }
        $date      = date('Y-m-d H:i:s');
        $save_data = [];
        $index     = 0;
        foreach ($jobs as $value) {
            if (empty($value['job'])) {
                return Response::make()->info('任务策略不能为空');
            }
            $save_data[] = [
                'strategy_id' => $value['job'],
                'sort'        => $index,
                'type'        => 0,
                'created_at'  => $date,
                'updated_at'  => $date,

            ];
            $index++;
        }

        try {
            DB::table('jobs')->where('type', 0)->delete();
            DB::table('jobs')->insert($save_data);
        } catch (\Exception $e) {
            return Response::make()->error('创建失败！');
        }

        return Response::make()->success('创建成功')->location('roles');
    }
}
