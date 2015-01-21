<?php defined('SYSPATH') or die('No direct script access.');?>
<hr>

<footer>
<div class="row">
<?foreach ( widgets::get('footer') as $widget):?>
<div class="col-md-3">
    <?=$widget->render()?>
</div>
<?endforeach?>
</div>



<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- footerHoriz -->
<ins class="adsbygoogle"
     style="display:inline-block;width:728px;height:90px"
     data-ad-client="ca-pub-1432664808275238"
     data-ad-slot="5847278709"></ins>
<script>
(adsbygoogle = window.adsbygoogle || []).push({});
</script>




<!--This is the license for Open Classifieds, do not remove -->
<div class="center-block">

	<p>&copy;
	<?if (Theme::get('premium')!=1):?>
	    Web Powered by <a href="http://open-classifieds.com?utm_source=<?=URL::base()?>&utm_medium=oc_footer&utm_campaign=<?=date('Y-m-d')?>" title="Best PHP Script Classifieds Software">Open Classifieds</a> 
	    2009 - <?=date('Y')?>
	<?else:?>
	    <?=core::config('general.site_name')?> <?=date('Y')?>
	<?endif?>    


	<?if(Core::config('appearance.theme_mobile')!=''):?>
	- <a href="<?=Route::url('default')?>?theme=<?=Core::config('appearance.theme_mobile')?>"><?=__('Mobile Version')?></a>
	<?endif?>
	</p>
</div>
</footer>
