<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<style>
    .tree{
        display: none;
        margin-left:10%;
    }
    .nav>li>a {
        position: relative;
        display: block;
        padding: 10px 15px;
        cursor: pointer;
    }
</style>

<?php echo $this->category;?>
<script>
    $(document).ready(function(){
        $('.tree-toggle').click(function () {
            $(this).css('background-color','#eee');
            $(this).parent().children('ul.tree').toggle(200);
        });
    })
</script>
