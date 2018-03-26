<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="search-product-big-title-area">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="search-product-bit-title text-center">
                    <h1><?php echo $this->h1; ?></h1>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-12 brands" style="min-height: 500px">
            <ul class="list-group">
                <?php foreach ($brands as $brand){?>
                    <?php if($brand['id_group']){?>
                        <li class="list-group-item" onclick="location.href='/search?search=<?php echo $brand['sku'];?>&id_group=<?php echo $brand['id_group'];?>'" style="cursor: pointer">
                            <a href="/search?search=<?php echo $brand['sku'];?>&id_group=<?php echo $brand['id_group'];?>">
                                <img src="<?php echo $brand['image'];?>">
                                <b><?php echo $brand['group_name'];?></b> <?php echo $brand['name'];?>
                            </a>
                        </li>
                    <?php }else{?>
                        <li class="list-group-item" onclick="location.href='/search?search=<?php echo $brand['sku'];?>&ID_art=<?php echo $brand['ID_art'];?>&brand=<?php echo $brand['brand'];?>'" style="cursor: pointer">
                            <a href="/search?search=<?php echo $brand['sku'];?>&ID_art=<?php echo $brand['ID_art'];?>&brand=<?php echo $brand['brand'];?>">
                                <img src="<?php echo $brand['image'];?>">
                                <b><?php echo $brand['brand'];?></b> <?php echo $brand['name'];?>
                            </a>
                        </li>
                    <?php } ?>

                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<script>
    $(document).ready(function(){
        console.log('ready');
    });
</script>