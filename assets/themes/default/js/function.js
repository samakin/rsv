$(document).ready(function(){

    $(".login-by").click(function(){
       var type = $(this).attr('data-type');

       switch (type) {
           case 'phone':
               $("#login-by-email").hide();
               $("[name='email']").val('');
               $("#login-by-phone").show();
               break;
           case 'email':
               $("#login-by-email").show();
               $("#login-by-phone").hide();
               $("[name='phone']").val('');
               break;
           default:
               $("#login-by-email").hide();
               $("#login-by-phone").show();
               break;
       }
    });
    $(".rating_form label").click(function(){
        var rate = $(this).attr('data-rate');
        $("#r"+rate).prop('checked', true);
        for(i = 1; i <= 5; i++){
            if(i<=rate){
                $(".fa-star.r"+i).addClass('active');
            }else{
                $(".fa-star.r"+i).removeClass('active');
            }
        }
    });



    $("#button_search,.list-group-item>a").click(function(e){
        if($("#search_input").val()){
            $("body").prepend('<div class="cssload-container"><div class="cssload-shaft1"></div><div class="cssload-shaft2"></div><div class="cssload-shaft3"></div><div class="cssload-shaft4"></div><div class="cssload-shaft5"></div><div class="cssload-shaft6"></div><div class="cssload-shaft7"></div><div class="cssload-shaft8"></div><div class="cssload-shaft9"></div><div class="cssload-shaft10"></div></div>');
        }
    });
    $('[rel="tooltip"]').tooltip();

    $("a[href='#top']").click(function(event) {
        event.preventDefault();
        $('html, body').animate({
            scrollTop: $(".container").offset().top
        }, 1000);
    });

    $(document).scroll(function() {
        var y = $(this).scrollTop();
        if (y > 200) {
            $("a[href='#top']").fadeIn();
            $('.search').addClass('search_fixed');
        } else {
            $("a[href='#top']").fadeOut();
            $('.search').removeClass('search_fixed');
        }
    });

    $(".vin_request").submit(function(event){
        send_request(event);
    });
    

    $("#newsletter").submit(function (event) {
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json){
                if(json['success']){
                    location.reload();
                } else {
                    $(".alert-danger").html(json['error']).fadeIn();
                }
            }
        });
    });

    $("#review_form").submit(function(e){
        e.preventDefault();
        $.ajax({
            url: '/ajax/review',
            method: 'post',
            data: $(this).serialize(),
            success: function (json) {
                if(json['success']){
                    location.reload();
                } else {
                    $(".alert-danger").html(json['error']).fadeIn();
                }
            }
        });
    });

    $("#call_back_form").submit(function (e) {
       e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json){
                if(json['success']){
                    location.reload();
                } else {
                    $(".alert-danger").html(json['error']).fadeIn();
                }
            }
        });
    });

    $("#fast_order_form").submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json){
                if(json['success']){
                    location.reload();
                } else {
                    $(".alert-danger").html(json['error']).fadeIn();
                }
            }
        });
    });

    $("#login_form").submit(function(event){
        event.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(json){
                if(json['success']){
                    location.reload();
                } else {
                    $(".alert-danger").html(json['error']).fadeIn();
                }
            }
        });
    });
});
function send_request(event){
    event.preventDefault();
    $.ajax({
        url: $('.vin_request').attr('action'),
        method: 'POST',
        data: $('.vin_request').serialize(),
        dataType: 'json',
        success: function(json){
            if(json['success']){
                $(".vin_request").trigger('reset');
                $(".alert-success").append(json['success']).fadeIn();
            } else {
                $(".alert-danger").append(json['error']).fadeIn();
            }
        }
    });
}

function add_cart(data, event){
    event.preventDefault();
    $.ajax({
        url: '/cart/add_cart',
        method: 'POST',
        dataType: 'json',
        data: data,
        success: function(json){

            if(json['success']){
                $(".product-count").html(json['product_count']).show();
                $(".cart-amunt").html(json['cart_amunt']);
                $("."+json['cartId']).show().css({fontSize:'1px'}).animate({
                    fontSize: '12px',
                }, 400 );
                $("#"+json['cartId']).show().css({fontSize:'1px'}).animate({
                    fontSize: '12px',
                }, 400 );
            }else{
                alert(json['error']);
            }
        }
    });
}

function catalog_search(ID_art, sku, brand, event){
    event.preventDefault();
    $("#search_modal").modal();
    get_search(ID_art, brand, sku);
}

function tecdoc_info(sku, brand, blockInfo){
    $("#popover").empty();
    $.ajax({
        url: '/ajax/get_tecdoc_info',
        method: 'POST',
        data: {sku:sku, brand:brand},
        success: function(json){
            if(blockInfo){
                $("."+blockInfo).html(json['html']);
            }else{
                $("#popover").html(json['html']).fadeIn('slow');
            }

        }
    });
}

function imgError(image, width, height) {
    image.onerror = "";
    if(width && height){
        $(image).css('width', width);
        $(image).css('height', height);
        image.src = "/image?img=/assets/themes/default/img/no_image.png&width="+width+"&height="+height;
    }else if(width){
        $(image).css('width', width);
        image.src = "/image?img=/assets/themes/default/img/no_image.png&width="+width;
    }else if(height){
        $(image).css('height', height);
        image.src = "/image?img=/assets/themes/default/img/no_image.png&height="+height;
    }else{
        image.src = "/image?img=/assets/themes/default/img/no_image.png";
    }

    return true;
}

function remove_garage(key,event) {
    event.preventDefault();
    $.ajax({
       url:'/ajax/remove_garage',
        method: 'post',
        data: {key:key},
        success: function(){
            $('.'+key).remove();
        }
    });
}

function fastOrder(href,e){
    e.preventDefault();
    if(href){
        $("#fast-order-product").val(href);
        $("#fast-order-modal").modal('show');
    }
}
