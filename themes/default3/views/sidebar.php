<?php defined('SYSPATH') or die('No direct script access.');?>
<div class="col-md-3 col-sm-12 col-xs-12"> 



<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- MovBig -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-1432664808275238"
     data-ad-slot="2697165906"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>



<?foreach ( widgets::get('sidebar') as $widget):?>
    <?if(get_class($widget) != 'Widget_Contact'):?>
        <div class="category_box_title custom_box">
        </div>
        <div class="well custom_box_content" >
            <?=$widget->render()?>
        </div>
   <?else:?>
        <?if(Request::current()->controller()=='ad' AND Request::current()->action()=='view'):?>
            <div class="category_box_title custom_box">
            </div>
            <div class="well custom_box_content" >
                <?=$widget->render()?>
            </div>
        <?endif?>
    <?endif?>
<?endforeach?>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- MovBig -->
<ins class="adsbygoogle"
     style="display:inline-block;width:300px;height:250px"
     data-ad-client="ca-pub-1432664808275238"
     data-ad-slot="2697165906"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>

<br>

<a target="blank" href="https://play.google.com/store/apps/details?id=com.wAnunciosGratisDominicanaCompraVainaCom"> 
Anuncio.DO Free Android App
<img src="/images/qrcode.23786183.png" alt="QR Code" >
</a>


</div>
