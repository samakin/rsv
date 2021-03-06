<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Content Wrapper. Contains page content -->
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        <small>#<?php echo $order['id']; ?></small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i>Главная</a></li>
        <li><a href="/autoxadmin/order"><?php echo lang('text_heading'); ?></a></li>
        <li class="active">#<?php echo $order['id']; ?></li>
    </ol>
</section>

<!-- Main content -->
<section class="invoice">
    <!-- Nav tabs -->


    <!-- title row -->
    <div class="row">
        <div class="col-xs-12">

            <ul class="nav nav-tabs" role="tablist">
                <li role="presentation" class="active"><a href="#tab1" aria-controls="tab2" role="tab"
                                                          data-toggle="tab"> Заказ #<?php echo $order['id']; ?>
                        <small><?php echo $order['created_at']; ?></small>
                    </a></li>
                <?php if ($delivery_view_form) { ?>
                    <li role="presentation"><a href="#tab2" aria-controls="tab2" role="tab"
                                               data-toggle="tab">Доставка</a></li>
                <?php } ?>
                <?php if ($ttns) { ?>
                    <li role="presentation"><a href="#tab3" aria-controls="tab2" role="tab" data-toggle="tab">ТТН</a>
                    </li>
                <?php } ?>

            </ul>


        </div><!-- /.col -->
    </div>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab1">
            <?php echo form_open('', ['id' => 'order_form']); ?>

            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <div class="form-group">
                        <label><?php echo lang('text_last_name'); ?></label>
                        <input type="text" name="last_name"
                               value="<?php echo set_value('last_name', $order['last_name']); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_first_name'); ?></label>
                        <input type="text" name="first_name"
                               value="<?php echo set_value('first_name', $order['first_name']); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_patronymic'); ?></label>
                        <input type="text" name="patronymic"
                               value="<?php echo set_value('patronymic', $order['patronymic']); ?>"
                               class="form-control">
                    </div>
                </div><!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <div class="form-group">
                        <label><?php echo lang('text_address'); ?></label>
                        <textarea rows="1" class="form-control"
                                  name="address"><?php echo set_value('address', $order['address']); ?></textarea>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_telephone'); ?></label>
                        <input type="text" name="telephone"
                               value="<?php echo set_value('telephone', $order['telephone']); ?>"
                               class="form-control">
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_email'); ?></label>
                        <input type="email" name="email" value="<?php echo set_value('email', $order['email']); ?>"
                               class="form-control">
                    </div>
                </div><!-- /.col -->
                <div class="col-sm-4 invoice-col">
                    <div class="form-group">
                        <label><?php echo lang('text_delivery_method'); ?></label>
                        <select name="delivery_method" class="form-control">
                            <?php foreach ($delivery as $delivery) { ?>
                                <option
                                        value="<?php echo $delivery['id']; ?>" <?php echo set_select('delivery_method_id', $delivery['id'], $delivery['id'] == $order['delivery_method_id']); ?>><?php echo $delivery['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label><?php echo lang('text_payment_method'); ?></label>
                        <select name="payment_method" class="form-control">
                            <?php foreach ($payment as $payment) { ?>
                                <option
                                        value="<?php echo $payment['id']; ?>" <?php echo set_select('payment_method_id', $payment['id'], $payment['id'] == $order['payment_method_id']); ?>><?php echo $payment['name']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>ID клиента</label>
                        <input required type="text" name="customer_id" value="<?php echo set_value('customer_id',$order['customer_id'] ? $order['customer_id'] : ''); ?>" class="form-control">
                    </div>

                    <?php if ($order['customer_id']) { ?>
                        <div class="form-group">
                            <a target="_blank"
                                   href="/autoxadmin/customer/edit/<?php echo $order['customer_id']; ?>"><?php echo $customer_info['first_name'] . ' ' . $customer_info['second_name']; ?></a>
                            <?php if ($black_list_info) { ?>
                                <p class="label label-danger">! <?php echo $black_list_info['comment']; ?></p>
                            <?php } else { ?>
                                <a href="#" onclick="addBlack(event)" class="btn btn-xs btn-default pull-right"> В
                                    черный список</a>
                            <?php } ?>
                            <br>Баланс: <?php echo format_balance($customer_info['balance']); ?>
                            Баланс в работе: <?php echo format_balance($this->customer_model->getWorkBalance($customer_info['id']));?>
                        </div>
                    <?php } ?>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row">
                <hr>
                <div class="col-lg-4 pull-right">
                    <div class="input-group">
                        <input autocomplete="off" id="search_val" type="text" class="form-control"
                               placeholder="Добавить в заказ">
                        <span class="input-group-btn">
                                <button id="search" class="btn btn-default"
                                        type="button"><?php echo lang('button_search'); ?></button>
                              </span>
                    </div><!-- /input-group -->
                    <div class="search-results"></div>
                </div><!-- /.col-lg-6 -->
                <div class="col-xs-12 table-responsive">
                    <table class="table table-condensed">
                        <thead>
                        <tr>
                            <th><?php echo lang('text_supplier'); ?></th>
                            <th><?php echo lang('text_product'); ?></th>
                            <th><?php echo lang('text_sku'); ?></th>
                            <th><?php echo lang('text_brand'); ?></th>
                            <th><?php echo lang('text_term'); ?></th>
                            <th>Доп. инф.</th>
                            <th><?php echo lang('text_qty'); ?></th>
                            <th><?php echo lang('text_delivery_price'); ?></th>
                            <th><?php echo lang('text_price'); ?></th>
                            <th><?php echo lang('text_subtotal'); ?></th>
                            <th><?php echo lang('text_status'); ?></th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody id="order-products">
                        <?php $row = 0; ?>
                        <?php if ($products) { ?>
                            <?php foreach ($products as $product) { ?>
                                <?php if (!$product['invoice_id']) { ?>
                                    <tr id="row<?php echo $product['id']; ?>"  style="color: <?php echo @$status[$product['status_id']]['color']; ?>;">
                                        <input type="hidden" name="products[<?php echo $product['id']; ?>][slug]"
                                               value="<?php echo $product['slug']; ?>">
                                        <input type="hidden" name="products[<?php echo $product['id']; ?>][product_id]"
                                               value="<?php echo $product['product_id']; ?>"/>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <input type="hidden"
                                                       name="products[<?php echo $product['id']; ?>][supplier_id]"
                                                       value="<?php echo $product['supplier_id']; ?>">
                                                <?php echo $supplier[$product['supplier_id']]['name']; ?>
                                            <?php } else { ?>
                                                <select name="products[<?php echo $product['id']; ?>][supplier_id]"
                                                        class="form-control">
                                                    <?php foreach ($supplier as $sp) { ?>
                                                        <option value="<?php echo $sp['id']; ?>"
                                                                <?php if ($product['supplier_id'] == $sp['id']){ ?>selected<?php } ?>><?php echo $sp['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } ?>
                                        <td>
                                            <?php echo $product['name']; ?>
                                            <input type="hidden" name="products[<?php echo $product['id']; ?>][name]"
                                                   value="<?php echo $product['name']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $product['sku']; ?>
                                            <input type="hidden" name="products[<?php echo $product['id']; ?>][sku]"
                                                   value="<?php echo $product['sku']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $product['brand']; ?>
                                            <input type="hidden" name="products[<?php echo $product['id']; ?>][brand]"
                                                   value="<?php echo $product['brand']; ?>">
                                        </td>
                                        <td>
                                            <?php echo @format_term($product['term']); ?>
                                            <input type="hidden" name="products[<?php echo $product['id']; ?>][term]"
                                                   value="<?php echo $product['term']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $product['excerpt']; ?>
                                        </td>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <input type="hidden"
                                                       name="products[<?php echo $product['id']; ?>][quantity]"
                                                       value="<?php echo $product['quantity']; ?>">
                                                <?php echo $product['quantity']; ?>
                                            <?php } else { ?>
                                                <input onkeyup="row_subtotal(<?php echo $product['id']; ?>)"
                                                       id="qty<?php echo $product['id']; ?>"
                                                       name="products[<?php echo $product['id']; ?>][quantity]"
                                                       type="text"
                                                       value="<?php echo $product['quantity']; ?>" class="form-control"
                                                       style="width: 80px;">
                                            <?php } ?>

                                        </td>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <input name="products[<?php echo $product['id']; ?>][delivery_price]"
                                                       type="hidden" value="<?php echo $product['delivery_price']; ?>">
                                                <?php echo $product['delivery_price']; ?>
                                            <?php } else { ?>
                                                <input
                                                        name="products[<?php echo $product['id']; ?>][delivery_price]"
                                                        type="text"
                                                        value="<?php echo $product['delivery_price']; ?>"
                                                        class="form-control"
                                                        style="width: 100px;">
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <input name="products[<?php echo $product['id']; ?>][price]"
                                                       type="hidden"
                                                       value="<?php echo $product['price']; ?>">
                                                <?php echo $product['price']; ?>
                                            <?php } else { ?>
                                                <input onkeyup="row_subtotal(<?php echo $product['id']; ?>)"
                                                       id="price<?php echo $product['id']; ?>"
                                                       name="products[<?php echo $product['id']; ?>][price]" type="text"
                                                       value="<?php echo $product['price']; ?>" class="form-control"
                                                       style="width: 100px;">
                                            <?php } ?>
                                        </td>
                                        <td><span
                                                    id="row_subtotal<?php echo $product['id']; ?>"><?php echo $product['quantity'] * $product['price'];
                                                ?></span></td>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <input type="hidden"
                                                       name="products[<?php echo $product['id']; ?>][status_id]"
                                                       value="<?php echo $product['status_id']; ?>">
                                                <?php echo $status[$product['status_id']]['name']; ?>
                                            <?php } else { ?>
                                                <select
                                                        style="border:  1px solid <?php echo @$status[$product['status_id']]['color']; ?>;"
                                                        name="products[<?php echo $product['id']; ?>][status_id]"
                                                        class="form-control">
                                                    <?php foreach ($status as $st) { ?>
                                                        <option
                                                                style="color: <?php echo @$st['color']; ?>;"
                                                                value="<?php echo $st['id']; ?>" <?php echo set_select('products[' . $row . '][status_id]', $st['id'], $st['id'] == $product['status_id']); ?>><?php echo $st['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } ?>
                                        </td>
                                        <td style="width: 90px">
                                            <a onclick="addInvoiceByItem(<?php echo $product['id']; ?>)" href=""
                                               class="btn btn-success"
                                               title="<?php echo lang('button_invoice'); ?>"><i
                                                        class="fa fa-file-text-o"></i></a>
                                            <a class="btn btn-danger confirm"
                                               href="/autoxadmin/order/delete_product?product_id=<?php echo $product['id']; ?>&order_id=<?php echo $order['id']; ?>">
                                                <i class="fa fa-trash-o"></i>
                                            </a>


                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php $row++;
                            } ?>
                            <?php foreach ($products as $product) { ?>
                                <?php if ($product['invoice_id']) { ?>
                                    <tr id="row<?php echo $product['id']; ?>" style="color: <?php echo @$status[$product['status_id']]['color']; ?>;">
                                        <input type="hidden" name="products[<?php echo $product['id']; ?>][slug]"
                                               value="<?php echo $product['slug']; ?>">
                                        <input type="hidden" name="products[<?php echo $product['id']; ?>][product_id]"
                                               value="<?php echo $product['product_id']; ?>"/>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <input type="hidden"
                                                       name="products[<?php echo $product['id']; ?>][supplier_id]"
                                                       value="<?php echo $product['supplier_id']; ?>">
                                                <?php echo @$supplier[$product['supplier_id']]['name']; ?>
                                            <?php } else { ?>
                                                <select name="products[<?php echo $product['id']; ?>][supplier_id]"
                                                        class="form-control">
                                                    <?php foreach ($supplier as $sp) { ?>
                                                        <option value="<?php echo $sp['id']; ?>"
                                                                <?php if ($product['supplier_id'] == $sp['id']){ ?>selected<?php } ?>><?php echo $sp['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } ?>
                                        <td>
                                            <?php echo $product['name']; ?>
                                            <input type="hidden" name="products[<?php echo $product['id']; ?>][name]"
                                                   value="<?php echo $product['name']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $product['sku']; ?>
                                            <input type="hidden" name="products[<?php echo $product['id']; ?>][sku]"
                                                   value="<?php echo $product['sku']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $product['brand']; ?>
                                            <input type="hidden" name="products[<?php echo $product['id']; ?>][brand]"
                                                   value="<?php echo $product['brand']; ?>">
                                        </td>
                                        <td>
                                            <?php echo @format_term($product['term']); ?>
                                            <input type="hidden" name="products[<?php echo $product['id']; ?>][term]"
                                                   value="<?php echo $product['term']; ?>">
                                        </td>
                                        <td>
                                            <?php echo $product['excerpt']; ?>
                                        </td>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <input type="hidden"
                                                       name="products[<?php echo $product['id']; ?>][quantity]"
                                                       value="<?php echo $product['quantity']; ?>">
                                                <?php echo $product['quantity']; ?>
                                            <?php } else { ?>
                                                <input onkeyup="row_subtotal(<?php echo $product['id']; ?>)"
                                                       id="qty<?php echo $product['id']; ?>"
                                                       name="products[<?php echo $product['id']; ?>][quantity]"
                                                       type="text"
                                                       value="<?php echo $product['quantity']; ?>" class="form-control"
                                                       style="width: 80px;">
                                            <?php } ?>

                                        </td>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <input name="products[<?php echo $product['id']; ?>][delivery_price]"
                                                       type="hidden" value="<?php echo $product['delivery_price']; ?>">
                                                <?php echo $product['delivery_price']; ?>
                                            <?php } else { ?>
                                                <input
                                                        name="products[<?php echo $product['id']; ?>][delivery_price]"
                                                        type="text"
                                                        value="<?php echo $product['delivery_price']; ?>"
                                                        class="form-control"
                                                        style="width: 100px;">
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <input name="products[<?php echo $product['id']; ?>][price]"
                                                       type="hidden"
                                                       value="<?php echo $product['price']; ?>">
                                                <?php echo $product['price']; ?>
                                            <?php } else { ?>
                                                <input onkeyup="row_subtotal(<?php echo $product['id']; ?>)"
                                                       id="price<?php echo $product['id']; ?>"
                                                       name="products[<?php echo $product['id']; ?>][price]" type="text"
                                                       value="<?php echo $product['price']; ?>" class="form-control"
                                                       style="width: 100px;">
                                            <?php } ?>
                                        </td>
                                        <td><span
                                                    id="row_subtotal<?php echo $product['id']; ?>"><?php echo $product['quantity'] * $product['price'];
                                                ?></span></td>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <input type="hidden"
                                                       name="products[<?php echo $product['id']; ?>][status_id]"
                                                       value="<?php echo $product['status_id']; ?>">
                                                <?php echo $status[$product['status_id']]['name']; ?>
                                            <?php } else { ?>
                                                <select name="products[<?php echo $product['id']; ?>][status_id]"
                                                        class="form-control">
                                                    <?php foreach ($status as $st) { ?>
                                                        <option
                                                                value="<?php echo $st['id']; ?>" <?php echo set_select('products[' . $row . '][status_id]', $st['id'], $st['id'] == $product['status_id']); ?>><?php echo $st['name']; ?></option>
                                                    <?php } ?>
                                                </select>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <?php if ($product['invoice_id']) { ?>
                                                <a href="/autoxadmin/invoice/edit/<?php echo $product['invoice_id']; ?>"><?php echo lang('text_invoice'); ?><?php echo $product['invoice_id']; ?></a>
                                            <?php } else { ?>
                                                <a class="btn btn-danger confirm"
                                                   href="/autoxadmin/order/delete_product?product_id=<?php echo $product['id']; ?>&order_id=<?php echo $order['id']; ?>">
                                                    <i class="fa fa-trash-o"></i>
                                                </a>
                                            <?php } ?>

                                        </td>
                                    </tr>
                                <?php } ?>
                                <?php $row++;
                            } ?>
                        <?php } ?>

                        </tbody>
                    </table>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <div class="row">
                <!-- accepted payments column -->
                <div class="col-xs-6 well">
                    <?php if (trim($order['comments']) != '') { ?>
                        <b><?php echo lang('text_comments'); ?></b>
                        <textarea disabled rows="3" name="comments" class="form-control"
                                  style="margin-top: 10px;"><?php echo $order['comments']; ?></textarea>
                        <hr>
                    <?php } ?>
                    <b><?php echo lang('text_manager_comments'); ?></b>
                    <textarea rows="3" name="history" class="form-control"></textarea>
                    <input type="checkbox" value="1" name="send_sms"><?php echo lang('text_send_sms'); ?>
                    <input type="checkbox" value="1" name="send_email"><?php echo lang('text_send_email'); ?>
                    <?php if ($history) { ?>
                        <hr>
                        <b>История по заказу</b>
                        <div class="list-group">
                            <?php foreach ($history as $history){?>
                                <?php echo format_time($history['date']); ?>
                                <span class="pull-right">
                                    <?php echo $history['manager']; ?>
                                </span>
                                <span  class="list-group-item">
                                    <p class="list-group-item-heading" style="word-break: break-all"><?php echo nl2br($history['text']); ?></p>
                                    <p style="text-align: right" class="text-muted"><?php if ($history['send_sms']) { ?>
                                            SMS <i class="fa fa-check-circle-o"></i>
                                        <?php } ?>
                                        <?php if ($history['send_email']) { ?>
                                            Email <i class="fa fa-check-circle-o"></i>
                                        <?php } ?></p>
                                </span>
                            <?php } ?>
                        </div>
                    <?php } ?>

                </div><!-- /.col -->
                <div class="col-xs-6">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width:50%"><?php echo lang('text_subtotal'); ?>:</th>
                                <td><span id="subtotal"><?php echo $subtotal; ?></span></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('text_shipping'); ?>:</th>
                                <td>
                                    <span id="delivery_price">
                                        <input type="text" name="delivery_price" class="form-control"
                                               value="<?php echo set_value('delivery_price', $order['delivery_price']); ?>">
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th><?php echo lang('text_commission'); ?></th>
                                <td><span id="commission"><?php echo $order['commission']; ?></span></td>
                            </tr>

                            <tr>
                                <th><?php echo lang('text_total'); ?>:</th>
                                <td><span id="total"><?php echo $order['total']; ?></span></td>
                            </tr>
                            <tr>
                                <th><?php echo lang('text_status'); ?></th>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" name="status">
                                            <?php foreach ($status as $st) { ?>
                                                <option
                                                        value="<?php echo $st['id']; ?>" <?php echo set_select('status', $st['id'], $st['id'] == $order['status']); ?>><?php echo $st['name']; ?></option>
                                            <?php } ?>
                                        </select>
                                        <input type="checkbox" name="set_products_status" value="1"> Применить к товарам
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><?php echo lang('text_revenue');?></th>
                                <td><span><?php echo $revenue; ?></span></td>
                            </tr>
                        </table>
                        <div class="pull-right">
                            <button onclick="addInvoiceByOrder(<?php echo $order['id']; ?>);" class="btn btn-success"
                                    title="<?php echo lang('button_invoice'); ?>"><i class="fa fa-file-text-o"></i>
                            </button>
                            <button class="btn btn-info btn-flat"
                                    type="submit"><?php echo lang('button_submit'); ?></button>
                            <a class="btn btn-default btn-flat"
                               href="/autoxadmin/order"><?php echo lang('button_close'); ?></a>
                        </div>
                    </div>
                </div><!-- /.col -->
            </div><!-- /.row -->
            <?php echo form_close(); ?>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab2">
            <div class="row">
                <?php if ($delivery_view_form) { ?>
                    <?php echo $delivery_view_form; ?>
                <?php } ?>
            </div>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab3">
            <div class="row">
                <div class="col-md-12">


                    <table class="table table-striped table-condensed">
                        <thead>
                        <tr>
                            <th>Номер</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($ttns as $ttn) { ?>
                            <tr>
                                <td><?php echo $ttn['ttn']; ?></td>
                                <td><?php echo $ttn['status']; ?></td>
                                <td>
                                    <div class="pull-right">
                                        <a href="<?php echo $ttn['delete']; ?>" class="btn btn-xs btn-danger confirm">Удалить</a>
                                        <a target="_blank" href="<?php echo $ttn['print']; ?>"
                                           class="btn btn-xs btn-info">Печать</a>
                                    </div>
                                </td>
                            </tr>
                        <?php } ?>

                        </tbody>
                    </table>


                </div>
            </div>
        </div>
    </div>
</section><!-- /.content -->
<div class="clearfix"></div>
<script>
    var row = '<?php echo $row;?>';

    $(document).keypress(function (e) {
        if (e.which == 13 && $("#search_val").is( ":focus" )) {
            e.preventDefault();
            $("#search").click();
        }
    });

    $(document).ready(function () {

        $("select").change(function () {
            $("[type='submit']").show();
        });

        $("#search").click(function (event) {
            var search = $("#search_val").val();
            event.preventDefault();
            $.ajax({
                url: '/autoxadmin/order/search_products',
                method: 'POST',
                data: {search: search},
                success: function (html) {
                    $(".search-results").html(html);
                }
            });
        });
    });


    function row_subtotal(product_id) {
        var price = $("#price" + product_id).val();
        var qty = $("#qty" + product_id).val();
        var sub_total = price * qty;
        $("#row_subtotal" + product_id).html(sub_total.toFixed(2));
    }

    //Добавдение товара к заказу
    function add_product(product_id, supplier_id, term) {
        $.ajax({
            url: '/autoxadmin/order/add_product',
            data: {
                product_id: product_id,
                supplier_id: supplier_id,
                term: term,
                order_id: '<?php echo $order['id'];?>'
            },
            method: 'POST',
            success: function (response) {
                if (response == 'success') {
                    location.reload();
                } else {
                    alert(response);
                }
            }
        });
    }

    function addBlack(e) {
        e.preventDefault();
        var comment = prompt('Комментарий');
        $.ajax({
            url: '/autoxadmin/blacklist/add',
            data: {comment: comment, customer_id: '<?php echo $order['customer_id'];?>'},
            method: 'post',
            success: function (response) {
                alert('Добавлен с черный список');
            }
        })
    }
</script>