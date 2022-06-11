<?php

namespace App\Admin\Controllers;
use App\Http\Controllers\Controller;
use App\Models\Field;
use Encore\Admin\Form;
use Encore\Admin\Show;

class FieldmanageController extends Controller
{
    /**
     * 显示给定用户的个人资料。
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function fieldmanage($id)
    {
        $form = new Form(new Field);
        $show = new Show(Field::findOrFail($id));
        $show->divider();

        $form->text('name', '表名');
        $form->text('alias_name', '表别名');
        return $form;
//        return view('admin.fieldmanage', [
//            'fieldlist' => Field::findOrFail($id)
//        ]);
    }

}
