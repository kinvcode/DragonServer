<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\TownCoordinate;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

class TownCoordinateController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new TownCoordinate(), function (Grid $grid) {
            $grid->column('name');
            $grid->column('word');
            $grid->column('area');
            $grid->column('x');
            $grid->column('y');
            $grid->column('created_at');
            $grid->column('updated_at');

            $grid->filter(function (Grid\Filter $filter) {
                $filter->expand();
                $filter->panel();
                $filter->like('name')->width("20%");
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
        return Show::make($id, new TownCoordinate(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('word');
            $show->field('area');
            $show->field('x');
            $show->field('y');
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
        return Form::make(new TownCoordinate(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('word');
            $form->text('area');
            $form->text('x');
            $form->text('y');

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
}
