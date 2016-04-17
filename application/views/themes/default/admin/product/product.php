<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<section class="content-header">
    <h3></h3>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#"><?php echo lang('text_heading');?></a></li>
    </ol>
</section>
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo lang('text_heading');?></h3>
                </div><!-- /.box-header -->
                <div class="box-body">
                    <table class="table table-bordered">
                        <tbody><tr>
                            <th><?php echo lang('text_sku');?></th>
                            <th><?php echo lang('text_brand');?></th>
                            <th><?php echo lang('text_name');?></th>
                            <th><?php echo lang('text_supplier_id');?></th>
                            <th><?php echo lang('text_delivery_price');?></th>
                            <th><?php echo lang('text_price');?></th>
                            <th><?php echo lang('text_saleprice');?></th>
                            <th><?php echo lang('text_status');?></th>
                            <th><a style="display: none;" href="/autoxadmin/product/create" class="btn btn-info pull-right"><?php echo lang('button_add');?></a></th>
                        </tr>
                        <?php echo form_open('', ['method' => 'GET']);?>
                        <tr>
                            <td>
                                <input type="text" name="sku" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="brand" class="form-control">
                            </td>
                            <td>
                                <input type="text" name="name" class="form-control">
                            </td>
                            <td>
                                <select name="supplier_id" class="form-control">
                                    <option></option>
                                    <?php foreach ($supplier as $sup){?>
                                        <option value="<?php echo $sup['id'];?>"><?php echo $sup['name'];?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>
                                <select name="status" class="form-control">
                                    <option></option>
                                    <option value="no"><?php echo lang('text_status_off');?></option>
                                    <option value="yes"><?php echo lang('text_status_on');?></option>
                                </select>
                            </td>
                            <td>
                                <button type="submit" class="btn btn-info"><?php echo lang('button_search');?></button>
                                <a href="/autoxadmin/product" class="btn btn-danger"><?php echo lang('button_reset');?></a>
                            </td>
                        </tr>
                        </form>
                        <?php if($products){?>

                            <?php foreach($products as $product){?>
                                <?php echo form_open();?>
                                <input type="hidden" name="slug" value="<?php echo $product['slug'];?>">
                                    <tr>
                                        <td><?php echo $product['sku'];?></td>
                                        <td>
                                            <?php echo $product['brand'];?>
                                        </td>
                                        <td><?php echo $product['name'];?></td>
                                        <td><?php echo $supplier[$product['supplier_id']]['name'];?></td>
                                        <td>
                                            <input type="text" name="delivery_price" value="<?php echo $product['delivery_price'];?>" class="form-control">
                                            <small><?php echo $currency[$product['currency_id']]['name'];?></small>
                                        </td>
                                        <td>
                                            <input type="text" name="price" value="<?php echo $product['price'];?>" class="form-control">
                                            <small><?php echo $currency[$product['currency_id']]['name'];?></small>
                                        </td>
                                        <td>
                                            <input type="text" name="saleprice" value="<?php echo $product['saleprice'];?>" class="form-control">
                                        </td>
                                        <td>
                                            <select name="status" class="form-control">
                                                <option value="0" <?php echo set_select('status',0, 0 == $product['status']);?>><?php echo lang('text_status_off');?></option>
                                                <option value="1" <?php echo set_select('status',0, 1 == $product['status']);?>><?php echo lang('text_status_on');?></option>
                                            </select>
                                        </td>
                                        <td>
                                            <div class="pull-right">
                                                <a href="/autoxadmin/product/delete/<?php echo $product['slug'];?>" type="button" class="btn btn-danger confirm"><?php echo lang('button_delete');?></a>
                                                <a style="display: none;" href="/autoxadmin/product/edit/<?php echo $product['slug'];?>" type="button" class="btn btn-info"><?php echo lang('button_edit');?></a>
                                                <button type="submit" class="btn btn-default"><?php echo lang('button_submit');?></button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links();?>
                </div>
            </div><!-- /.box -->
        </div><!-- /.col -->
    </div>
</section><!-- /.content -->
<script>
    $("input, select").change(function () {
        $(".btn-default").show();
    });
</script>