<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo $this->title;?></title>
    <meta name="description" content="<?php echo $this->description;?>">
    <meta name="keywords" content="<?php echo $this->keywords;?>">
    <link rel="shortcut icon" href="/favicon.ico" type="image/ico">
    <!-- Google Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Play:400,700&subset=latin,cyrillic' rel='stylesheet' type='text/css'>
    <!-- Bootstrap -->
    <link rel="stylesheet" href="<?php echo theme_url();?>css/bootstrap.min.css">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo theme_url();?>css/font-awesome.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo theme_url();?>css/owl.carousel.css">
    <link rel="stylesheet" href="<?php echo theme_url();?>css/responsive.css">
    <link rel="stylesheet" href="<?php echo theme_url();?>style.css">
    <?php if($this->config->item('my_style')){?>
        <link rel="stylesheet" href="<?php echo $this->config->item('my_style');?>">
    <?php } ?>
</head>
<body>

<div class="header-area">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <div class="user-menu">
                    <ul>
                        <?php if($this->is_login){?>
                            <li><a href="/customer"><i class="fa fa-user"></i><?php echo lang('text_account');?></a></li>
                            <li><a href="/cart"><?php echo lang('text_cart');?></a></li>
                            <li><a href="/customer/logout"><?php echo lang('text_logout');?></a></li>
                        <?php }else{?>
                            <li><a href="#" data-toggle="modal" data-target="#login"><i class="fa fa-user"></i><?php echo lang('text_login_link');?></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </div>

            <div class="col-md-5">
                <div class="pull-right" id="contact">
                    <?php foreach(explode(';',$this->contacts['phone']) as $phone){?>
                        <i class="fa fa-phone-square"></i> <?php echo $phone;?>&nbsp;
                    <?php } ?>
                    <?php foreach(explode(';',$this->contacts['email']) as $email){?>
                        <i class="fa fa-envelope"></i> <a href="mailto:<?php echo $email;?>"><?php echo $email;?></a>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End header area -->

<div class="site-branding-area">
    <div class="container">
        <div class="row">
            <div class="col-sm-3">
                <div class="logo">
                    <?php if($this->config->item('company_logo')){?>
                        <a href="/"><img src="<?php echo $this->config->item('logo');?>"/></a>
                    <?php }else{?>
                        <a href="/"><?php echo $this->config->item('company_name');?></a>
                    <?php } ?>

                </div>
            </div>

            <div class="col-sm-6">
                    <div class="search">
                        <?php echo form_open('ajax/pre_search', ['method' => 'get', 'class' => 'search_form']);?>
                            <input required type="text" id="search_input" name="search" class="input-text" placeholder="OC90">
                            <input type="submit" data-value="<?php echo lang('button_search');?>" value="<?php echo lang('button_search');?>" class="button alt">
                        </form>
                    </div>
            </div>

            <div class="col-sm-3">
                <div class="shopping-item">
                    <a href="/cart"><?php echo lang('text_cart');?> - <span class="cart-amunt"><?php echo format_currency($this->cart->total());?></span> <i class="fa fa-shopping-cart"></i>
                         <span
                        <?php if($this->cart->total_items() == 0){?>
                            style="display: none;"
                        <?php } ?>
                            class="product-count"><?php echo $this->cart->total_items();?></span>

                    </a>
                </div>
            </div>
        </div>
    </div>
</div> <!-- End site branding area -->

<div class="mainmenu-area">
    <div class="container">
        <div class="row">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
            </div>
            <div class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="/"><?php echo lang('text_home');?></a></li>
                    <?php if($this->header_page){?>
                        <?php foreach($this->header_page as $hpage){?>
                            <li><a target="<?php echo $hpage['target'];?>" href="<?php echo $hpage['href'];?>" title="<?php echo $hpage['title'];?>"><?php echo $hpage['menu_title'];?></a></li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div> <!-- End mainmenu area -->
<?php if($this->error){?>
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4><i class="icon fa fa-ban"></i> Warning!</h4>
        <?php echo $this->error;?>
    </div>
<?php } ?>
<?php if($this->success){?>
    <div class="alert alert-success alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
        <h4>	<i class="icon fa fa-check"></i> Success!</h4>
        <?php echo $this->success;?>.
    </div>
<?php } ?>
