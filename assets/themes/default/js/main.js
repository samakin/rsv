jQuery(document).ready(function(a){a(".mainmenu-area").sticky({topSpacing:0});a(".product-carousel").owlCarousel({loop:true,nav:true,margin:20,responsiveClass:true,responsive:{0:{items:1},600:{items:3},1000:{items:5}}});a(".related-products-carousel").owlCarousel({loop:true,nav:true,margin:20,responsiveClass:true,responsive:{0:{items:1},600:{items:2},1000:{items:2},1200:{items:3}}});a(".brand-list").owlCarousel({loop:true,nav:true,margin:20,responsiveClass:true,responsive:{0:{items:1},600:{items:3},1000:{items:4}}});a(".navbar-nav li a").click(function(){a(".navbar-collapse").removeClass("in")});a(".navbar-nav li a, .scroll-to-up").bind("click",function(c){var b=a(this);var d=a(".header-area").outerHeight();a("html, body").stop().animate({scrollTop:a(b.attr("href")).offset().top-d+"px"},1200,"easeInOutExpo");c.preventDefault()});a("body").scrollspy({target:".navbar-collapse",offset:95});if(a(window).width()<767){a(".mainmenu-area ul.navbar-nav li > ul").parent().find(a("a")).after('<i class="glyphicon glyphicon-chevron-down"></i>');a(".nav>li > a + i").click(function(){a(this).parent().find(a("ul")).toggleClass("pod_menu_mob")})}});