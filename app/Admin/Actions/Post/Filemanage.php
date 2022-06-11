<?php

namespace App\Admin\Actions\Post;

use Encore\Admin\Actions\RowAction;
use Illuminate\Database\Eloquent\Model;

class Filemanage extends RowAction
{
    public $name = '字段管理';

    public function href()
    {
        return "/admin/fieldmanage/".$this->getKey();
    }
}
