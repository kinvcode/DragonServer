<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\AccountJob;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class AccountJobController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new AccountJob(), function (Grid $grid) {
            $grid->column('id')->sortable();
            $grid->column('account');
            $grid->column('raw');
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
        return Show::make($id, new AccountJob(), function (Show $show) {
            $show->field('id');
            $show->field('account');
            $show->field('raw');
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
        return Form::make(new AccountJob(), function (Form $form) {
            $form->display('id');
            $form->text('account');
            $form->text('raw');
        
            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
