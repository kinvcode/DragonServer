<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\DnfRole;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class DnfRoleController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new DnfRole(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('account');
            $grid->column('role_id');
            $grid->column('name');
            $grid->column('character');
            $grid->column('advancement');
            $grid->column('awakening');
            $grid->column('level');
            $grid->column('prestige');
            $grid->column('position');
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
        return Show::make($id, new DnfRole(), function (Show $show) {
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
}
