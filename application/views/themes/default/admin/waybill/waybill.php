<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

defined('BASEPATH') OR exit('No direct script access allowed');?>
<html>
<head>
    <link rel="stylesheet" href="<?php echo theme_url();?>admin/bootstrap/css/bootstrap.min.css">

</head>
<body>
<!-- Main content -->
<section class="content">
    <?php if($results){?>
        <?php foreach ($results as $delivery){?>
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $delivery['delivery_method'];?></h3>
                </div>
                <div class="panel-body">
                    <?php foreach ($delivery['addresses'] as $address){?>
                        <div class="row">
                            <div class="col-md-4">
                                <?php echo $address['telephone'];?><br>
                                <?php echo $address['last_name'];?> <?php echo $address['first_name'];?> <br>
                                <?php echo $address['address'];?>
                            </div>
                            <div class="col-md-8">
                                <table border="1px" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th>Артикул</th>
                                        <th>Бренд</th>
                                        <th>Название</th>
                                        <th>Количество</th>
                                    </tr>
                                    </thead>
                                    <?php foreach ($address['products'] as $product){?>
                                        <tr>
                                            <td><?php echo $product['sku'];?></td>
                                            <td><?php echo $product['brand'];?></td>
                                            <td><?php echo $product['name'];?></td>
                                            <td><?php echo $product['quantity'];?></td>
                                        </tr>
                                    <? } ?>
                                </table>
                            </div>
                        </div>
                        <hr>
                    <?php } ?>
                </div>
            </div>
            <div style="page-break-before:always;">
        <?php } ?>
    <?php } ?>
                <button class="btn btn-default pull-right" onclick="this.remove();print();">Печать</button>
</section><!-- /.content -->
</body>
</html>
