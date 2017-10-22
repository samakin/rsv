<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="single-product-area" xmlns="http://www.w3.org/1999/html">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <?php $this->load->view('customer/menu');?>
            </div>
            <div class="col-md-9">
                <h2>Детали в работе</h2>

                    <table class="table table-bordered table-striped">
                        <thead>
                        <?php echo form_open('',['method' => 'get']);?>
                        <tr>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="order_id" class="form-control" value="<?php echo $this->input->get('order_id');?>" style="width: 60px">
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="name" class="form-control" value="<?php echo $this->input->get('name');?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="sku" class="form-control" value="<?php echo $this->input->get('sku');?>">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <input type="text" name="brand" class="form-control" value="<?php echo $this->input->get('brand');?>">
                                </div>
                            </td>
                            <td></td>
                            <td>
                                <div class="form-group">
                                    <input style="width: 80px" type="text" name="quantity" class="form-control" value="">
                                </div>
                            </td>
                            <td>
                                <div class="form-group">
                                    <select name="status_id" class="form-control">
                                        <option></option>
                                        <?php foreach ($statuses as $status){?>
                                            <option <?php echo set_select('status_id',$status['id'],$this->input->get('status_id') == $status['id']);?> value="<?php echo $status['id'];?>"><?php echo $status['name'];?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group pull-right">
                                    <a href="/customer/products" type="button" class="btn btn-link">Сброс</a>
                                    <button type="submit" class="btn btn-xs pull-right">Поиск</button>
                                </div>
                            </td>
                        </tr>
                        </form>
                        </thead>
                        <tbody>
                        <tr>
                            <th style="width: 10px">Заказ</th>
                            <th>Дата</th>
                            <th>Наименование</th>
                            <th>Артикул</th>
                            <th>Производитель</th>
                            <th>Цена</th>
                            <th>Количество</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                        <?php if($products){?>
                            <?php foreach ($products as $product){?>
                                <tr>
                                    <td>
                                        <a target="_blank" href="/customer/orderinfo/<?php echo $product['order_id'];?>">
                                            <b><?php echo $product['order_id'];?></b>
                                        </a>
                                    </td>
                                    <td><small><?php echo $product['created_at'];?></small></td>
                                    <td><?php echo $product['name'];?></td>
                                    <td><?php echo $product['sku'];?></td>
                                    <td><?php echo $product['brand'];?></td>
                                    <td><?php echo format_currency($product['price']);?></td>
                                    <td><?php echo $product['quantity'];?></td>
                                    <td>
                                        <b style="color: <?php echo @$statuses[$product['status_id']]['color'];?>">
                                            <?php echo @$statuses[$product['status_id']]['name'];?>
                                        </b>
                                    </td>
                                    <td>
                                        <?php if($product['parcel_id']){?>
                                            <a href="/customer/print_parcel/<?php echo $product['parcel_id'];?>" target="_blank">
                                                <?php if($product['ttn']){?>
                                                    <small>ТТН:<?php echo $product['ttn'];?></small>
                                                <?php } ?>
                                                <?php if($product['parcel_id']){?>
                                                    <small>Посылка:<?php echo $product['parcel_id'];?></small>
                                                <?php } ?>
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                        </tbody>
                    </table>
                    <?php echo $this->pagination->create_links();?>

            </div>
        </div>
    </div>
</div>