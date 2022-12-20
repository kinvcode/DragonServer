<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\DnfAccount;
use App\Admin\Repositories\DnfRole;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class DnfAccountController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new DnfAccount(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('qq');
            $grid->column('role', '角色配置')->display(function () {
                $str = <<<STR
<a href="/admin/account/$this->qq" class="btn btn-success">配置角色</a>
STR;

                return $str;
            });
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');

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
        return Show::make($id, new DnfAccount(), function (Show $show) {
            $show->field('id');
            $show->field('qq');
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
        return Form::make(new DnfAccount(), function (Form $form) {
            $form->display('id');
            $form->text('qq');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }

    public function accounts($qq, Content $content)
    {
        $grid = Grid::make(new DnfRole(['jobs']), function (Grid $grid) use ($qq) {
            $grid->model()
                ->where('account', $qq)
                ->where('role_id', '<>', '')
                ->orderBy('position');

            $grid->column('role_id');
            $grid->column('position');
            $grid->column('name');
            $grid->column('strategy', '策略')->display(function () {
                if ($this->jobs->count()) {
                    return '<b class="text-custom">已配置</b>';
                } else {
                    return '<b class="text-danger-darker">未配置</b>';
                }
            });

            $grid->toolsWithOutline(false);
            $grid->disableCreateButton();
            $grid->disableDeleteButton();
            $grid->disableEditButton();
            $grid->disableViewButton();

            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $role_id = (int)$this->role_id;
                // append一个操作
                $actions->append('<a href="/admin/role/' . $role_id . '/job"><i class="feather icon-edit"></i>配置任务</a>');
            });
        });
        return $content
            ->title('角色配置管理')
            ->description()
            ->body($grid);
    }
}
