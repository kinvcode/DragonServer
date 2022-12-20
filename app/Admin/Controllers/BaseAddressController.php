<?php

namespace App\Admin\Controllers;

use App\Admin\Repositories\BaseAddress;
use Dcat\Admin\Actions\Response;
use Dcat\Admin\Admin;
use Dcat\Admin\Form;
use Dcat\Admin\Grid;
use Dcat\Admin\Layout\Content;
use Dcat\Admin\Show;
use Dcat\Admin\Http\Controllers\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\exactly;

class BaseAddressController extends AdminController
{
    private $address_map_data = [
        "新人物基址"     => "C_USER_ADDRESS",
        "人物CALL"       => "C_USER_CALL",
        "名称偏移"       => "C_NAME_OFFSET",
        "鞋子偏移"       => "C_SHOE_OFFSET",
        "攻击速度"       => "C_ATTACK_SPEED",
        "移动速度"       => "C_MOVE_SPEED",
        "释放速度"       => "C_CASTING_SPEED",
        "全局空白"       => "C_EMPTY_ADDRESS",
        "汇编CALL"       => "C_ASM_CALL",
        "召唤人偶CALL"   => "C_SUMMON_FIGURE",
        "召唤怪物CALL"   => "C_SUMMON_MONSTER",
        "透明CALL"       => "C_HIDDEN_CALL",
        "技能CALL"       => "C_SKILL_CALL",
        "游戏状态"       => "C_GAME_STATUS",
        "房间编号"       => "C_ROOM_NUMBER",
        "时间基址"       => "C_TIME_ADDRESS",
        "门型偏移"       => "C_DOOR_TYPE_OFFSET",
        "篝火判断"       => "C_BONFIRE_JUDGE",
        "评分基址"       => "C_SCORE_ADDRESS",
        "C_E_评分"       => "C_CE_SCORE",
        "是否开门"       => "C_DOOR_OFFSET",
        "地图偏移"       => "C_MAP_OFFSET",
        "BOSS房间X"      => "C_BOSS_ROOM_X",
        "BOSS房间Y"      => "C_BOSS_ROOM_Y",
        "当前房间X"      => "C_CURRENT_ROOM_X",
        "当前房间Y"      => "C_CURRENT_ROOM_Y",
        "起始坐标X"      => "C_BEGIN_ROOM_X",
        "起始坐标Y"      => "C_BEGIN_ROOM_Y",
        "地图开始2"      => "C_MAP_HEAD",
        "地图结束2"      => "C_MAP_END",
        "类型偏移"       => "C_TYPE_OFFSET",
        "阵营偏移"       => "C_CAMP_OFFSET",
        "代码偏移"       => "C_CODE_OFFSET",
        "怪物血量"       => "C_MONSTER_BLOOD",
        "读取坐标"       => "C_READ_COORDINATE",
        "对象坐标"       => "C_OBJECT_COORDINATE",
        "全局基址"       => "C_GLOBAL_ADDRESS",
        "顺图偏移"       => "C_PASS_ROOM_OFFSET",
        "坐标顺图CALL"   => "C_COORDINATE_PASS_ROOM",
        "自动捡物"       => "C_AUTO_PICKUP",
        "动作ID"         => "C_MOVEMENT_ID",
        "地图穿透"       => "C_PENETRATE_MAP",
        "建筑穿透"       => "C_PENETRATE_BUILDING",
        "宽高偏移"       => "C_WH_OFFSET",
        "数组偏移"       => "C_AISLE_OFFSET",
        "浮点冷却2"      => "C_FLOAT_COOL_DOWN2",
        "索引偏移"       => "C_MAP_CODE",
        "冷却判断CALL"   => "C_COOL_DOWN_JUDGE_CALL",
        "技能栏"         => "C_SKILL_LIST",
        "技能栏偏移"     => "C_SKILL_LIST_OFFSET",
        "坐标CALL偏移"   => "C_COOR_CALL_OFFSET",
        "区域参数"       => "C_AREA_PARAM",
        "区域CALL"       => "C_AREA_CALL",
        "区域偏移"       => "C_AREA_OFFSET",
        "地图名称"       => "C_MAP_NAME",
        "副本编号"       => "C_MAP_NUMBER",
        "选图基址"       => "C_MAP_SELECTED",
        "选图基址2"      => "C_MAP2_SELECTED",
        "选中地图编号"   => "C_MAP_CODE_SELECTED",
        "选中地图难度"   => "C_MAP_DIFFICULTY_SELECTED",
        "发包基址"       => "C_PACKET_SEND",
        "缓冲CALL"       => "C_BUFFER_CALL",
        "发包CALL"       => "C_PACKET_SEND_CALL",
        "加密CALL"       => "C_ENCRYPT_CALL",
        "对话基址"       => "C_DIALOGUE",
        "对话基址B"      => "C_DIALOGUE_B",
        "Esc对话基址"    => "C_DIALOGUE_ESC",
        "最大疲劳"       => "C_FATIGUE_MAX",
        "当前疲劳"       => "C_FATIGUE_CURRENT",
        "角色等级"       => "C_USER_LEVEL",
        "人物名望"       => "C_USER_PRESTIGE",
        "城镇大区域"     => "C_TOWN_WORLD",
        "城镇小区域"     => "C_TOWN_AREA",
        "城镇坐标X"      => "C_TOWN_X",
        "城镇坐标Y"      => "C_TOWN_Y",
        "加密包CALL"     => "C_ENCRYPT_PACKET_CALL",
        "加密包CALL2"    => "C_ENCRYPT_PACKET_CALL2",
        "加密包CALL4"    => "C_ENCRYPT_PACKET_CALL4",
        "加密包CALL8"    => "C_ENCRYPT_PACKET_CALL8",
        "满血偏移"       => "C_FULL_HP_OFFSET",
        "满蓝偏移"       => "C_FULL_MP_OFFSET",
        "当前蓝量"       => "C_CUR_MP",
        "角色指针"       => "C_ROLE_POINTER",
        "帐号基址"       => "C_ACCOUNT",
        "制裁基址"       => "C_SANCTION",
        "脚下物品"       => "C_ITEM_UNDER_FOOTER",
        "通关判断基址"   => "C_CLEARANCE",
        "通关商店"       => "C_CLEARANCE_SHOP",
        "界面指针"       => "C_PANEL",
        "地面物品"       => "C_GROUND_ITEM",
        "物品名称"       => "C_ITEM_NAME",
        "物品栏"         => "C_PACKAGE",
        "物品栏偏移"     => "C_PACKAGE_OFFSET",
        "当前负重"       => "C_CUR_WEIGHT",
        "最大负重"       => "C_MAX_WEIGHT",
        "背包基址"       => "C_PACKAGE_ADDRESS",
        "装备品级"       => "C_EQ_GRADE",
        "角色基址"       => "C_ROLE_ADDRESS",
        "角色初始指针"   => "C_ROLE_INIT_P",
        "偏爱角色位置"   => "C_FAVORITE_ROLE_POS",
        "普通角色位置"   => "C_COMMON_ROLE_POS",
        "装备名望"       => "C_EQ_PRESTIGE",
        "界面_Rcx"       => "C_PANEL_RCX",
        "界面CALL"       => "C_PANEL_CALL",
        "商店基址"       => "C_SHOP",
        "学习技能基址"   => "C_LEARN_SKILL",
        "学习技能CALL"   => "C_LEARN_SKILL_CALL",
        "学习技能偏移"   => "C_LEARN_SKILL_OFFSET",
        "技能名称"       => "C_SKILL_NAME",
        "技能移动CALL"   => "C_MOV_SKILL_CALL",
        "任务基址"       => "C_JOB_ADDRESS",
        "全部任务首地址" => "C_ALL_JOB_HEAD",
        "全部任务尾地址" => "C_ALL_JOB_END",
        "任务类型"       => "C_JOB_TYPE",
        "任务条件"       => "C_JOB_REQUIRE",
        "已接任务首地址" => "C_ACCEPTED_JOB_HEAD",
        "已接任务尾地址" => "C_ACCEPTED_JOB_END",
        "完成CALL"       => "C_FINISH_CALL",
        "提交CALL"       => "C_SUBMIT_CALL",
        "接受CALL"       => "C_ACCEPT_CALL",
        "数量偏移"       => "C_ITEM_NUM",
        "物品代码"       => "C_ITEM_CODE",
        "任务副本"       => "C_JOB_MAP"
    ];

    protected $xinyue = [
        '装备栏偏移'   => 'C_PACKAGE1_OF',
        '消耗栏偏移'   => 'C_PACKAGE2_OF',
        '材料栏偏移'   => 'C_PACKAGE3_OF',
        '副职业栏偏移' => 'C_PACKAGE4_OF',
        '任务栏偏移'   => 'C_PACKAGE5_OF',
        '徽章栏偏移'   => 'C_PACKAGE6_OF',
        '晶块仓库栏'   => 'C_PACKAGE7_OF',
        '灵魂仓库栏'   => 'C_PACKAGE8_OF',
    ];

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Grid::make(new BaseAddress(), function (Grid $grid) {
            $qidu = <<<STR
<a href="/admin/address/qidu" class="btn btn-primary">
    <i class="feather icon-rotate-cw"></i><span class="d-none d-sm-inline">&nbsp;&nbsp;更新七度基址</span>
</a>
STR;

            $xinyue = <<<STR
<a href="/admin/address/xinyue" class="btn btn-primary">
    <i class="feather icon-rotate-cw"></i><span class="d-none d-sm-inline">&nbsp;&nbsp;更新心悦基址</span>
</a>
STR;

            $grid->toolsWithOutline(false);
            $grid->disableCreateButton();
            $grid->tools($qidu);
            $grid->tools($xinyue);
            $grid->paginate(2000);
            //$grid->disablePerPages();
            $grid->column('id')->sortable();
            $grid->column('name');
            $grid->column('const_name');
            $grid->column('address');
            $grid->column('hex')->display(function ($hex) {
                return '0x' . strtoupper(dechex($this->address));
            });
//            $grid->column('created_at');
//            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->expand();
                $filter->panel();
                $filter->equal('id');
                $filter->like('const_name');
                $filter->like('name');
                $filter->equal('address');
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
        return Show::make($id, new BaseAddress(), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('address');
//            $show->field('created_at');
//            $show->field('updated_at');
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Form::make(new BaseAddress(), function (Form $form) {
            $form->display('id');
            $form->text('name');
            $form->text('const_name');
            $form->text('address');
//            $form->display('created_at');
//            $form->display('updated_at');
        });
    }

    /**
     * Edit interface.
     *
     * @param mixed $id
     * @param Content $content
     * @return Content
     */
    public function edit($id, Content $content)
    {
        return $content
            ->translation($this->translation())
            ->title($this->title())
            ->description($this->description()['edit'] ?? trans('admin.edit'))
            ->body($this->form()->edit($id));
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function parseForm()
    {
        $map_data = $this->address_map_data;
        return Form::make(new BaseAddress(), function (Form $form) {
            $form->hidden('name')->creationRules('nullable');
            $form->hidden('const_name')->creationRules('nullable');
            $form->hidden('address')->creationRules('nullable');
            $form->file('file', '七度基址文件');
        })->submitted(function (Form $form) use ($map_data) {
            $save_data  = [];
            $origin_str = request()->file('_file_')->get();
            $str        = mb_convert_encoding($origin_str, "UTF-8", "GBK");
            $str_arr    = explode("\r\n", $str);
            foreach ($str_arr as $value) {
                if (mb_strpos($value, ".常量") === false) {
                    continue;
                } else {
                    $value    = str_replace(".常量 ", "", $value);
                    $value    = str_replace(",  ", "", $value);
                    $value    = str_replace(" 7度_获取", "", $value);
                    $line_arr = explode(',', $value);
                    if (isset($line_arr[2]) && isset($map_data[$line_arr[0]])) {
                        $save_data[] = [
                            "name"       => $line_arr[0], // 名称
                            "const_name" => $map_data[$line_arr[0]], // 常量名称
                            "address"    => $line_arr[1], // 十进制
                        ];
                    }
                }
            }

            DB::table('base_address')->truncate();
            DB::table('base_address')->insert($save_data);

            return $form->response()->success("处理成功！")->redirect('base-address');
        })->disableSubmitButton()->disableViewButton()->disableCreatingCheck();
    }

    /**
     * Create interface.
     *
     * @param Content $content
     * @return Content
     */
    public function create(Content $content)
    {
        return $content
            ->translation($this->translation())
            ->title($this->title())
            ->description($this->description()['create'] ?? trans('admin.create'))
            ->body($this->parseForm());
    }

    public function store()
    {
        return $this->parseForm()->store();
    }

    public function qiduForm(Content $content)
    {
        $form = Form::make(new BaseAddress(), function (Form $form) {
            $form->file('file', '七度基址文件');
        })->disableSubmitButton()
            ->disableEditingCheck()
            ->disableViewCheck()
            ->disableCreatingCheck()
            ->disableListButton()
            ->action('/address/qidu');

        return $content
            ->title('更新七度基址')
            ->description()
            ->body($form);
    }

    public function updateQidu(Request $request)
    {
        $map_data   = $this->address_map_data;
        $origin_str = $request->file('_file_')->get();
        $str        = mb_convert_encoding($origin_str, "UTF-8", "GBK");
        $str_arr    = explode("\r\n", $str);
        foreach ($str_arr as $value) {
            if (mb_strpos($value, ".常量") === false) {
                continue;
            } else {
                $value    = str_replace(".常量 ", "", $value);
                $value    = str_replace(",  ", "", $value);
                $value    = str_replace(" 7度_获取", "", $value);
                $line_arr = explode(',', $value);
                if (isset($line_arr[2]) && isset($map_data[$line_arr[0]])) {
                    $save_data               = [
                        "name"       => $line_arr[0], // 名称
                        "const_name" => $map_data[$line_arr[0]], // 常量名称
                        "address"    => $line_arr[1], // 十进制
                    ];
                    $key                     = $line_arr[0];
                    $save_data['updated_at'] = date('Y-m-d H:i:s');
                    DB::table('base_address')
                        ->updateOrInsert(['name' => $key, 'const_name' => $map_data[$key]], $save_data);
                }
            }
        }
        $form = new Form();
        return $form->response()->success("处理成功！")->redirect('base-address');
    }

    public function xinyueForm(Content $content)
    {
        $form = Form::make(new BaseAddress(), function (Form $form) {
            $form->file('file', '心悦基址文件');
        })->disableSubmitButton()
            ->disableEditingCheck()
            ->disableViewCheck()
            ->disableCreatingCheck()
            ->disableListButton()
            ->action('/address/xinyue');

        return $content
            ->title('更新心悦基址')
            ->description()
            ->body($form);
    }

    public function updateXinyue(Request $request)
    {
        $map_data   = $this->xinyue;
        $origin_str = $request->file('_file_')->get();
        $str        = mb_convert_encoding($origin_str, "UTF-8", "GBK");
        $str_arr    = explode("\r\n", $str);
        foreach ($str_arr as $value) {
            if (mb_strpos($value, ".常量") === false) {
                continue;
            } else {
                $value    = str_replace(".常量 ", "", $value);
                $value    = str_replace("  ", "", $value);
                $value    = str_replace(" , 心悦获取", "", $value);
                $line_arr = explode(',', $value);

                if ($line_arr[0] === '时装帽子位置') {
                    break;
                }

                $line2_arr       = explode('·', $line_arr[1]);
                $new_line_arr    = [];
                $new_line_arr[0] = $line_arr[0];
                $new_line_arr[1] = $line2_arr[0];
                $new_line_arr[2] = $line2_arr[1];

                $line_arr = $new_line_arr;
                if (isset($line_arr[2]) && isset($map_data[$line_arr[0]])) {
                    $save_data               = [
                        "name"       => $line_arr[0], // 名称
                        "const_name" => $map_data[$line_arr[0]], // 常量名称
                        "address"    => $line_arr[1], // 十进制
                    ];
                    $key                     = $line_arr[0];
                    $save_data['updated_at'] = date('Y-m-d H:i:s');
//                    dump($key);
//                    dd($map_data[$key]);
                    DB::table('base_address')
                        ->updateOrInsert(['name' => $key, 'const_name' => $map_data[$key]], $save_data);
                }
            }
        }
        $form = new Form();
        return $form->response()->success("处理成功！")->redirect('base-address');
    }
}
