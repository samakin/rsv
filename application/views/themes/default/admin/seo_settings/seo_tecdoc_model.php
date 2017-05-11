<?php
/**
 * Developer: Распутний Сергей Викторович
 * Site: cms.autoxcatalog.com
 * Email: sergey.rasputniy@gmail.com
 */
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php echo form_open();?>

<section class="content-header">
    <h1>
        SEO настройки
        <small>tecdoc модели</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="/autoxadmin"><i class="fa fa-dashboard"></i> Главная</a></li>
        <li><a href="/autoxadmin/seo_settings">SEO настройки</a></li>
        <li class="active">tecdoc модели</li>
    </ol>
</section>
<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">SEO шаблон tecdoc производители</h3>
        </div>
        <div class="box-body">

            <div class="form-group">
                <label>SEO title</label>
                <input type="text" name="seo_tecdoc_model[title]" value="<?php echo $seo_tecdoc_model['title'];?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO Description</label>
                <input type="text" name="seo_tecdoc_model[description]" value="<?php echo $seo_tecdoc_model['description'];?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO keywords</label>
                <input type="text" name="seo_tecdoc_model[keywords]" value="<?php echo $seo_tecdoc_model['keywords'];?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO h1</label>
                <input type="text" name="seo_tecdoc_model[h1]" value="<?php echo $seo_tecdoc_model['h1'];?>" class="form-control">
            </div>
            <div class="form-group">
                <label>SEO text</label>
                <textarea class="textarea" name="seo_tecdoc_model[text]"><?php echo set_value('text',@$seo_tecdoc_model['text']);?></textarea>
            </div>
            <p class="help-block">
                {manuf} - Название производителя<br>
                {model} - Название модели<br>
            </p>
        </div>
        <div class="box-footer">
            <button type="submit" class="btn btn-info pull-right">Сохранить</button>
        </div>
    </div>
</section>
</form>
