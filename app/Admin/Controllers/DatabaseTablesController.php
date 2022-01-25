<?php

namespace App\Admin\Controllers;

use App\Models\Database_tables;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Show;
use Encore\Admin\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\Request;

class DatabaseTablesController extends AdminController
{

    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Database_tables';

    /**
     * Make a table builder.
     *
     * @return Table
     */
    protected function table()
    {
        $table = new Table(new Database_tables());

        $table->column('id', __('Id'));
        $table->column('name', __('Name'));
        $table->column('alias_name', __('Alias name'));
        $table->column('created_at', __('Created at'));
        $table->column('updated_at', __('Updated at'));
        $table->column('creator.username', __('Creator Username'));
        $table->column('modifier.username', __('Modifier Username'));

        return $table;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Database_tables::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('alias_name', __('Alias name'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('creator_id', __('Creator id'));
        $show->field('modifier_id', __('Modifier id'));

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
        $form->text('name', __('Name'));
        $form->text('alias_name', __('Alias name'));

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
            }


            if($form->isEditing()){
                $status_updated = $datatables->where('id', $form->getResourceId())
                    ->update(
                        [
                            'modifier_id' => $userArray['id'],
                            'name' => $form->name,
                            'alias_name' => $form->alias_name,
                        ]
                    );

                //如果表存在 并且数据表更新成功，则更改数据表名；目前获取不到旧的值，后期再做；先注释
                if(Schema::hasTable($form->name) && $status_updated){
                    $request = request();
                    $request->flash();
                    dd($request->session());
                    Schema::rename($request->old('name'), $form->name);
                }
            }
        });
        return $form;
    }

    /**
     * 留学声列表
     * @author dongxuepeng
     * @desc 删除操作：数据表删除、数据表相关的外键数据全部清空；目前不清楚如何拦截删除操作，暂时不做；
     * @date 2022/01/25
     * @return array
     */
//    protected function delete(){
//
//    }
}
