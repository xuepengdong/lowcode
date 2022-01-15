<?php

namespace App\Admin\Controllers;

use App\Models\Database_tables;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Show;
use Encore\Admin\Table;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
        $userArray = Auth::guard('admin')->user()->toArray();

        $form->text('name', __('Name'));
        $form->text('alias_name', __('Alias name'));
        $form->text('creator_id', __('Creator id'))->value($userArray['id']);
        $form->text('modifier_id', __('Modifier id'))->default($userArray['id'])->value($userArray['id']);

//        if($form->isCreating()){
//
////            Schema::create($form->name, function($table){
////                $table->increments('id');
////            });
//        }else{
//
//        }
        return $form;
    }
}
