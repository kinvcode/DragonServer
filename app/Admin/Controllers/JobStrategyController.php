<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\JobStrategy;
use App\Models\Dungeon;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Http\JsonResponse;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Dcat\Admin\Widgets\Card;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobStrategyController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $type_map = [
            0 => '刷图',
            1 => '剧情',
            2 => '每日任务',
        ];

        return Grid::make(new JobStrategy(), function (Grid $grid) use ($type_map) {
            $dungeon   = <<<STR
<a href="/admin/strategy/dungeon" class="btn btn-primary">
    <i class="feather icon-plus"></i><span class="d-none d-sm-inline">&nbsp;&nbsp;刷图策略</span>
</a>
STR;
            $main_line = <<<STR2
<a href="/admin/strategy/mainline" class="btn btn-primary">
    <i class="feather icon-plus"></i><span class="d-none d-sm-inline">&nbsp;&nbsp;剧情策略</span>
</a>
STR2;
            $daily = <<<STR3
<a href="/admin/strategy/daily" class="btn btn-primary">
    <i class="feather icon-plus"></i><span class="d-none d-sm-inline">&nbsp;&nbsp;每日任务策略</span>
</a>
STR3;

            $grid->toolsWithOutline(false);
            $grid->disableCreateButton();
            $grid->tools($dungeon);
            $grid->tools($main_line);
            $grid->tools($daily);
            $grid->column('id')->sortable();
            $grid->column('type')->display(function ($type) use ($type_map) {
                return $type_map[$type];
            });
            $grid->column('name');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
            });

            $grid->disableViewButton();
            $grid->disableEditButton();
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
        return Show::make($id, new JobStrategy(), function (Show $show) {
            $show->field('id');
            $show->field('job_id');
            $show->field('name');
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
        return Form::make(new JobStrategy(), function (Form $form) {
            $form->display('id');
            $form->text('job_id');
            $form->text('name');

            $form->display('created_at');
            $form->display('updated_at');
        })->deleting(function (Form $form) {
            $count = DB::table('jobs')->where('strategy_id', $form->getKey())->count();
            if ($count) {
                return $form->response()->warning('当前有任务使用该策略中，不允许删除！');
            }
        });
    }

    public function create(Content $content)
    {
        $job_types = DB::table('jobs')->select(['id', 'name'])->get()->toJson();
        $data      = compact(['job_types']);
        return $content
            ->translation($this->translation())
            ->title($this->title())
            ->description($this->description()['create'] ?? trans('admin.create'))
            ->body(view('admin.create_job_strategy', $data));
    }

    // 刷图策略编辑界面
    public function dungeon(Content $content)
    {
        $form = Form::make(null, function (Form $form) {
            $form->disableHeader();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableViewCheck();

            $form->text('name', '策略名称')->maxLength(100)->required();

            $form->table('map', '刷图地图', function ($table) {
                $table->select('dungeon', '地图')->options('/map-options');
                $table->number('numbers', '次数')->min(-1);
            })->saving(function ($v) {
                return json_encode($v);
            });
        });

        $form->action('strategy/dungeon');

        $card  = new Card("刷图次数设置为0，则一直刷此图");
        $card2 = new Card("刷图顺序按照以下地图排列进行");
        return $content
            ->title('创建刷图策略')
            ->description()
            ->body($card)
            ->body($card2)
            ->body($form);
    }

    // 地图选项
    public function mapOptions()
    {
        $data     = DB::table('dungeons')->select(['code', 'name'])->get();
        $response = [];
        foreach ($data as $value) {
            $response[] = [
                'id'   => $value->code,
                'text' => $value->name
            ];
        }
        return $response;
    }

    // 保存刷图策略
    public function saveDungeon(Request $request)
    {
        $name = $request->input('name');
        $maps = $request->input('map');
        foreach ($maps as $key => $value) {
            if ($value['_remove_'] == '1') {
                unset($maps[$key]);
            }
        }

        if (count($maps) < 1) {
            return Response::make()->info('地图列表不能为空');
        }

        $json_data = [];
        foreach ($maps as $value) {
            if (empty($value['dungeon'])) {
                return Response::make()->info('地图不能为空');
            }
            $json_data[] = [
                'dungeon_code' => (int)$value['dungeon'],
                'times'        => (int)$value['numbers']
            ];
        }
        $json_data = json_encode($json_data);

        $date = date('Y-m-d H:i:s');
        try {
            DB::table('job_strategies')->insert(['type' => 0, 'name' => $name, 'raw' => $json_data, 'created_at' => $date, 'updated_at' => $date]);
        } catch (\Exception $e) {
            return Response::make()->error('创建失败！');
        }

        return Response::make()->success('创建成功')->location('job/strategies');
    }

    // 主线剧情编辑界面
    public function mainline(Content $content)
    {
        $form = Form::make(null, function (Form $form) {
            $form->disableHeader();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableViewCheck();

            $form->text('name', '策略名称')->maxLength(100)->required();
        });

        $form->action('strategy/mainline');

        return $content
            ->title('创建刷图策略')
            ->description()
            ->body($form);
    }

    // 保存剧情策略
    public function saveMainline(Request $request)
    {
        $name = $request->input('name');
        $data = [
            'type' => 1,
            'name' => $name,
            'raw'  => json_encode([]),
        ];
        try {
            \App\Models\JobStrategy::create($data);
        } catch (\Exception $e) {
            return Response::make()->error('创建失败！');
        }
        return Response::make()->success('创建成功')->location('job/strategies');
    }

    public function daily(Content $content)
    {
        $form = Form::make(null, function (Form $form) {
            $form->disableHeader();
            $form->disableEditingCheck();
            $form->disableCreatingCheck();
            $form->disableViewCheck();

            $form->text('name', '策略名称')->maxLength(100)->required();
        });

        $form->action('strategy/daily');

        return $content
            ->title('创建每日任务策略')
            ->description()
            ->body($form);
    }

    public function saveDaily(Request $request)
    {
        $name = $request->input('name');
        $data = [
            'type' => 2,
            'name' => $name,
            'raw'  => json_encode([]),
        ];
        try {
            \App\Models\JobStrategy::create($data);
        } catch (\Exception $e) {
            return Response::make()->error('创建失败！');
        }
        return Response::make()->success('创建成功')->location('job/strategies');

    }
}
