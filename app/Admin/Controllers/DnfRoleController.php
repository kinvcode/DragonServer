<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\DnfRole;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;

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
        10  => [
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
            $grid->column('id')->sortable();
            $grid->column('account');
            $grid->column('role_id')->display(function ($role_id) {
                return $role_id ?? '未获取';
            });
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
