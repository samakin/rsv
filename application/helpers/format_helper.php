<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */

function format_currency($value){
    $CI = &get_instance();
    return
        $CI->default_currency['symbol_left']
        .round($value,$CI->default_currency['decimal_place'])
        .$CI->default_currency['symbol_right'];
}

function format_quantity($value){
    if($value > 5){
        return '>5'.lang('text_st');
    }else{
        return $value.lang('text_st');
    }
}

function format_term($term){
    if($term >= 24){
        $day = $term / 24;
        return $day.lang('text_day');
    }elseif($term == 0){
        return lang('text_in_stock');
    }

    return $term.lang('text_time');
}

function format_category($categories){
    $return = '<ul>';
    foreach ($categories as $category){
        $return .= '<li><a href="/category/'.$category['slug'].'">'.$category['name'].'</a>';
        if($category['brands']){
            $return .= '<ul>';
            foreach ($category['brands'] as $brand){
                $return .= '<li><a href="/category/'.$category['slug'].'/brand/'.$brand['brand'].'">'.$brand['brand'].'</a>';
            }
            $return .= '</ul>';
        }
        if($category['children']){
            format_category($category['children']);
        }
        $return .= '</li>';

    }
    $return .= '</ul>';
    return $return;
}

function plural_form($number, $after) {
    /* варианты написания для количества 1, 2 и 5 */
    $cases = array (2, 0, 1, 1, 1, 2);
    return $number.' '.$after[ ($number%100>4 && $number%100<20)? 2: $cases[min($number%10, 5)] ];
}

function format_data($data){
    return substr($data, 0, 4)."-".substr($data, 4, 2);
}