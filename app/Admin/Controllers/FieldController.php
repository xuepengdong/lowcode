<?php

namespace App\Admin\Controllers;

use App\Models\Field;
use Encore\Admin\Form;
use Encore\Admin\Http\Controllers\AdminController;
use Encore\Admin\Show;
use Encore\Admin\Table;

class FieldController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Field';

    /**
     * Make a table builder.
     *
     * @return Table
     */
    protected function table()
    {
        $table = new Table(new Field());

        $table->column('id', __('Id'));
        $table->column('modelid', __('Modelid'));
        $table->column('name', __('Name'));
        $table->column('alias_name', __('Alias name'));
        $table->column('is_system', __('Is system'));

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
        $show = new Show(Field::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('modelid', __('Modelid'));
        $show->field('name', __('Name'));
        $show->field('alias_name', __('Alias name'));
        $show->field('is_system', __('Is system'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Field());

        $form->number('modelid', __('Modelid'));
        $form->text('name', __('Name'));
        $form->text('alias_name', __('Alias name'));
        $form->text('is_system', __('Is system'))->default('否');

        return $form;
    }
}
