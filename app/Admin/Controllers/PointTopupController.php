<?php

namespace App\Admin\Controllers;

use DB;
use App\Models\PointTopup;
use App\Models\User;
use App\Models\AdminUser;
use App\Models\CustomerType;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class PointTopupController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PointTopup';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PointTopup());

        $grid->column('id', __('Id'));
        $grid->column('user.name', __('User id'));
        $grid->column('amount', __('Amount'));
        $grid->column('discount', __('Discount'));
        $grid->column('added_amount', __('Added amount'));
        $grid->column('original_amount', __('Original amount'));
        $grid->column('next_amount', __('Next amount'));
        $grid->column('customer_type', __('Customer type'));
        $grid->column('customer_accumulated_amount', __('Customer accumulated amount'));
        $grid->column('unit.name', __('Unit id'));
        $grid->column('staff.name', __('Staff id'));
        $grid->column('sale.name', __('Sale id'));
        $grid->column('status', __('Status'))->using(Constant::STATUS);
        $grid->column('payment_method', __('Payment method'))->using(Constant::PAYMENT_METHOD);
        $grid->column('bill', __('Bill'))->image();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->actions(function ($actions) {
            $actions->disableDelete();
        });
        $grid->enableHotKeys();
        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(PointTopup::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('user_id', __('User id'));
        $show->field('amount', __('Amount'));
        $show->field('discount', __('Discount'));
        $show->field('added_amount', __('Added amount'));
        $show->field('original_amount', __('Original amount'));
        $show->field('next_amount', __('Next amount'));
        $show->field('customer_type', __('Customer type'));
        $show->field('customer_accumulated_amount', __('Customer accumulated amount'));
        $show->field('unit_id', __('Unit id'));
        $show->field('staff_id', __('Staff id'));
        $show->field('sale_id', __('Sale id'));
        $show->field('status', __('Status'));
        $show->field('payment_method', __('Payment method'));
        $show->field('bill', __('Bill'));
        $show->panel()->tools(function ($tools) {
            $tools->disableEdit();
            $tools->disableDelete();
        });
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PointTopup());

        $form->select('user_id', __('User id'))->options(User::select(DB::raw('CONCAT(name, " - ", IFNULL(phone_number,"")) AS name, id'))
        ->where('unit_id', '=', Admin::user()->active_unit_id)->pluck('name', 'id'))->required()->setWidth(2,2);
        $form->currency('amount', __('Amount'))->symbol('VND');
        $form->currency('discount', __('Discount'))->readonly()->setWidth(2,2);
        $form->currency('added_amount', __('Added amount'))->readonly()->setWidth(2,2);
        $form->currency('original_amount', __('Original amount'))->readonly()->setWidth(2,2);
        $form->currency('next_amount', __('Next amount'))->readonly()->setWidth(2,2);
        $form->select('customer_type', __('Customer type'))->options(CustomerType::pluck('name', 'id'))->readonly()->setWidth(2,2);
        $form->currency('customer_accumulated_amount', __('Customer accumulated amount'))->readonly()->setWidth(2,2);
        $form->hidden('unit_id', __('Unit id'))->default(Admin::user()->active_unit_id);
        $form->hidden('staff_id', __('Staff id'))->default(Admin::user()->id);
        $form->select('sale_id', __('Sale id'))->options(AdminUser::select(DB::raw('CONCAT(name, " - ", IFNULL(phone_number,"")) AS name, id'))
        ->where('active_unit_id', '=', Admin::user()->active_unit_id)->pluck('name', 'id'))->required()->setWidth(2,2);
        $form->hidden('status', __('Status'))->default(1);
        $form->select('payment_method', __('Payment method'))->options(Constant::PAYMENT_METHOD)->required()->setWidth(2,2);
        $form->file('bill', __('Bill'));
        $form->tools(function (Form\Tools $tools) {
            $tools->disableDelete();
        });

        $url = env('APP_URL') . '/api/customer';
        $script = <<<EOT
        var userInfo = [0,0];
        $(document).on('change', ".user_id", function () {
            $.get("$url",{q : this.value}, function (data) {
                userInfo = [data[0].point, data[1].discount];
                console.log(userInfo);
                $(".discount").val(data[1].discount);
                $(".customer_type").val(data[1].name);
                $(".customer_accumulated_amount").val(data[0].accumulated_amount);
                $(".original_amount").val(data[0].point);
            });
        });
        $(".amount").on("input", function() {
            var amount = parseInt($(this).val().replaceAll(",", ""));
            $(".added_amount").val(amount);
            $(".next_amount").val(userInfo[1] + amount);
         });
        EOT;

        Admin::script($script);

        return $form;
    }
}