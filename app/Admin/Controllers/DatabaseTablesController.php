<?php

namespace App\Admin\Controllers;

use App\Models\Database_tables;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Show;
use Encore\Admin\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\DB;

class DatabaseTablesController extends AdminController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Database_tables';

    /**
     * @desc 列表展示
     *
     * @return Table
     */
    protected function table()
    {
        $table = new Table(new Database_tables());

        $table->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableView();
        });

        $table->filter(function($filter){
            $filter->column(1/2, function ($filter) {
                $filter->like('name', '表名');
                $filter->like('alias_name', '中文名字');
            });

            $filter->column(1/2, function ($filter) {
                $filter->between('created_at', '创建时间')->datetime();
                $filter->between('updated_at', '修改时间')->datetime();
            });
        });

        $table->disableExport();
        $table->disableRowSelector();
        $table->column('id', __('Id'));
        $table->column('name', __('表名'));
        $table->column('alias_name', __('表别名'));
        $table->column('created_at', __('创建时间'));
        $table->column('updated_at', __('更新时间'));
        $table->column('creator.username', __('创建者'));
        $table->column('modifier.username', __('修改者'));

        return $table;
    }

    /**
     * @desc 详情页
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Database_tables::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('表名'));
        $show->field('alias_name', __('表别名'));
        $show->field('created_at', __('创建时间'));
        $show->field('updated_at', __('更新时间'));
        $show->field('creator.username', __('创建者'));
        $show->field('modifier.username', __('修改者'));

        return $show;
    }


    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Database_tables());

        $form->tools(function (Form\Tools $tools) {
            // 去掉`删除`按钮
            $tools->disableDelete();
            // 去掉`查看`按钮
            $tools->disableView();
        });

        $form->text('name', __('表名（英文）'))
            ->creationRules(['required', "unique:database_tables"])
            ->updateRules(['required', "unique:database_tables"]);

        $form->text('alias_name', __('中文'))
            ->creationRules(['required', "unique:database_tables"])
            ->updateRules(['required', "unique:database_tables"]);

        $old_array = Database_tables::where('id', '=', $form->getResourceId())->get();
        $userArray = Auth::guard('admin')->user()->toArray();

        Redis::hset('old_databases', $form->getResourceId().'_'.$userArray['id'], json_encode($old_array));

        $form->saved(function (Form $form) {
            $datatables = new Database_tables();
            $userArray = Auth::guard('admin')->user()->toArray();
            if($form->isCreating()){
                //保存后回调，更新创建人ID
                $datatables->where('name', $form->name)
                    ->update(['creator_id' => $userArray['id']]);

                //保存后回调，生成对应数据库
                Schema::create($form->name, function($table){
                    $table->increments('id');
                    $table->timestamp('created_at');
                    $table->timestamp('updated_at')->nullable();
                    $table->timestamp('deleted_at')->nullable();
                    $table->integer('creator_id');
                    $table->integer('modifier_id')->nullable();
                });

                //生成field表中的字段
                $database_tables = DB::table('database_tables')->where('name', $form->name)->first();

                $data = [
                    ["modelid" => $database_tables->id, "name" => "id",  "alias_name" => "ID", 'is_system'=>'是'],
                    ["modelid" => $database_tables->id, "name" => "created_at", "alias_name" => "创建时间", 'is_system'=>'是'],
                    ["modelid" => $database_tables->id, "name" => "updated_at", "alias_name" => "修改时间", 'is_system'=>'是'],
                    ["modelid" => $database_tables->id, "name" => "deleted_at", "alias_name" => "删除时间", 'is_system'=>'是'],
                    ["modelid" => $database_tables->id, "name" => "creator_id", "alias_name" => "创建者ID", 'is_system'=>'是'],
                    ["modelid" => $database_tables->id, "name" => "modifier_id", "alias_name" => "修改者ID", 'is_system'=>'是'],
                    ["modelid" => $database_tables->id, "name" => "is_system", "alias_name" => "是否为系统字段", 'is_system'=>'是'],
                ];

                DB::table('field')->insert($data);

            }

            if($form->isEditing()){
                $status_updated = Database_tables::where('id', '=', $form->getResourceId())->update(
                    [
                        'modifier_id' => $userArray['id'],
                        'name' => $form->name,
                        'alias_name' => $form->alias_name,
                    ]
                );
                $old_databases = json_decode(Redis::hget('old_databases', $form->getResourceId().'_'.$userArray['id']), true);
                if(Schema::hasTable($old_databases[0]['name']) && $status_updated == 0){
                    Schema::rename($old_databases[0]['name'], $form->name);
                }
            }
        });
        return $form;
    }

    /**
     * 数据表的删除操作
     * @author dongxuepeng
     * @desc 删除操作：数据表删除、数据表相关的外键数据全部清空；目前不清楚如何拦截删除操作，暂时不做；
     * @date 2022/01/25
     * @return array
     */
    protected function delete(){

    }
}
