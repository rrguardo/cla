<?php defined('SYSPATH') or die('No direct script access.');?>

	
<?=Form::errors()?>
<div class="page-header">
	<h1><?=__('Advertisement Configuration')?></h1>
    <p class=""><?=__('List of optional fields. To activate/deactivate select "TRUE/FALSE" in desired field.')?></p>

</div>


<div class="well">
	<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
		<fieldset>
			<?foreach ($config as $c):?>
			<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
			<?endforeach?>
			<div class="form-group">
				<?= FORM::label($forms['num_images']['key'], __('Number of images'), array('class'=>'control-label col-sm-3', 'for'=>$forms['num_images']['key']))?>
				<div class="col-sm-4">
					<?= FORM::input($forms['num_images']['key'], $forms['num_images']['value'], array(
					'placeholder' => "4", 
					'class' => 'tips form-control', 
					'id' => $forms['num_images']['key'], 
					'data-original-title'=> __("Number of images"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Number of images displayed"),
					))?> 
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label($forms['parent_category']['key'], __('Parent category'), array('class'=>'control-label col-sm-3', 'for'=>$forms['parent_category']['key']))?>
				<div class="col-sm-4">
					<?= FORM::select($forms['parent_category']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['parent_category']['value'], array(
					'placeholder' => "", 
					'class' => 'tips form-control', 
					'id' => $forms['parent_category']['key'], 
					'data-original-title'=> __("parent_category field"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Use parent categories"),
					))?>  
				</div>
			</div>
			
			<div class="form-group">
				<?= FORM::label($forms['expire_date']['key'], __('Ad expiration date'), array('class'=>'control-label col-sm-3', 'for'=>$forms['expire_date']['key']))?>
				<div class="col-sm-4">
					<div class="input-group">
						<?= FORM::input($forms['expire_date']['key'], $forms['expire_date']['value'], array(
						'placeholder' => $forms['expire_date']['value'], 
						'class' => 'tips form-control col-sm-3', 
						'id' => $forms['expire_date']['key'], 
						'data-original-title'=> __("Expire days"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-content'=>__('After how many days an Ad will expire. 0 for never'),
						));?>
						<span class="input-group-addon"><?=__("Days")?></span>
					</div> 
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label($forms['ads_in_home']['key'], __('Advertisements in home'), array('class'=>'control-label col-sm-3', 'for'=>$forms['ads_in_home']['key']))?>
				<div class="col-sm-4">
					<?= FORM::select($forms['ads_in_home']['key'], array(0=>__('Latest Ads'),1=>__('Featured Ads'),2=>__('Popular Ads last month')), $forms['ads_in_home']['value'], array(
					'placeholder' => $forms['ads_in_home']['value'], 
					'class' => 'tips form-control ', 
					'id' => $forms['ads_in_home']['key'],
					'data-content'=> __("You can choose what ads you want to display in home."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Advertisements in home"), 
					))?> 
				</div>
			</div>
            <div class="form-group">
                <?= FORM::label($forms['related']['key'], __('Related ads'), array('class'=>'control-label col-sm-3', 'for'=>$forms['related']['key']))?>
                <div class="col-sm-4">
                    <?= FORM::input($forms['related']['key'], $forms['related']['value'], array(
                    'placeholder' => $forms['related']['value'], 
                    'class' => 'tips form-control ', 
                    'id' => $forms['related']['key'],
                    'data-content'=> __("You can choose if tehres random related ads displayed at the advertisement"),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-original-title'=>__("Related ads"), 
                    ))?> 
                </div>
            </div>
			<div class="form-group">
				<?= FORM::label($forms['address']['key'], __('Address'), array('class'=>'control-label col-sm-3', 'for'=>$forms['address']['key']))?>
				<div class="col-sm-4">
					<?= FORM::select($forms['address']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['address']['value'], array(
					'placeholder' => "", 
					'class' => 'tips form-control', 
					'id' => $forms['address']['key'], 
					'data-original-title'=> __("Address field"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Displays the field Address in the Ad form."),
					))?> 
				</div>
			</div>
            <div class="form-group">
                <?= FORM::label($forms['map']['key'], __('Google Maps in Ad'), array('class'=>'control-label col-sm-3', 'for'=>$forms['map']['key']))?>
                <div class="col-sm-4">
                    <?= FORM::select($forms['map']['key'], array(0=>"FALSE",1=>"TRUE"),$forms['map']['value'], array(
                    'placeholder' => "", 
                    'class' => 'tips form-control', 
                    'id' => $forms['map']['key'], 
                    'data-original-title'=> 'Google Maps',
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-content'=>__("Displays the google maps in the Ad."),
                    ))?> 
                </div>
            </div>
            <div class="form-group">
                <?= FORM::label($forms['map_pub_new']['key'], __('Google Maps in Publish New'), array('class'=>'control-label col-sm-3', 'for'=>$forms['map_pub_new']['key']))?>
                <div class="col-sm-4">
                    <?= FORM::select($forms['map_pub_new']['key'], array(0=>"FALSE",1=>"TRUE"),$forms['map_pub_new']['value'], array(
                    'placeholder' => "", 
                    'class' => 'tips form-control', 
                    'id' => $forms['map_pub_new']['key'], 
                    'data-original-title'=> 'Google Maps',
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-content'=>__("Displays the google maps in the Publish new form."),
                    ))?> 
                </div>
            </div>
            <div class="form-group">
				<?= FORM::label($forms['map_zoom']['key'], __('Google map zoom level'), array('class'=>'control-label col-sm-3', 'for'=>$forms['map_zoom']['key']))?>
				<div class="col-sm-4">
					<?= FORM::input($forms['map_zoom']['key'], $forms['map_zoom']['value'], array(
					'placeholder' => "16", 
					'class' => 'tips form-control', 
					'id' => $forms['map_zoom']['key'], 
					'data-original-title'=> __("Zoom level"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Google map default zoom level "),
					))?> 
				</div>
			</div>
            
            <div class="form-group">
                <?= FORM::label($forms['center_lat']['key'], __('Map latitude coordinates'), array('class'=>'control-label col-sm-3', 'for'=>$forms['center_lat']['key']))?>
                <div class="col-sm-4">
                    <?= FORM::input($forms['center_lat']['key'], $forms['center_lat']['value'], array(
                    'placeholder' => "40", 
                    'class' => 'tips form-control', 
                    'id' => $forms['center_lat']['key'], 
                    'data-original-title'=> __("Latitude coordinates"),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-content'=>__("Google map default latitude coordinates"),
                    ))?> 
                </div>
            </div>

			<div class="form-group">
				<?= FORM::label($forms['center_lon']['key'], __('Map longitude coordinates'), array('class'=>'control-label col-sm-3', 'for'=>$forms['center_lon']['key']))?>
				<div class="col-sm-4">
					<?= FORM::input($forms['center_lon']['key'], $forms['center_lon']['value'], array(
					'placeholder' => "3", 
					'class' => 'tips form-control', 
					'id' => $forms['center_lon']['key'], 
					'data-original-title'=> __("Longitude coordinates"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Google map default longitude coordinates"),
					))?> 
				</div>
			</div>
			
			<div class="form-group">
				<?= FORM::label($forms['phone']['key'], __('Phone'), array('class'=>'control-label col-sm-3', 'for'=>$forms['phone']['key']))?>
				<div class="col-sm-4">
					<?= FORM::select($forms['phone']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['phone']['value'], array(
					'placeholder' => "", 
					'class' => 'tips form-control', 
					'id' => $forms['phone']['key'], 
					'data-original-title'=> __("Phone field"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Displays the field Phone in the Ad form."),
					))?> 
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label($forms['website']['key'], __('Website'), array('class'=>'control-label col-sm-3', 'for'=>$forms['website']['key']))?>
				<div class="col-sm-4">
					<?= FORM::select($forms['website']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['website']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips form-control', 
					'id' => $forms['website']['key'], 
					'data-original-title'=> __("Website field"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Displays the field Website in the Ad form."),
					))?> 
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label($forms['location']['key'], __('Location'), array('class'=>'control-label col-sm-3', 'for'=>$forms['location']['key']))?>
				<div class="col-sm-4">
					<?= FORM::select($forms['location']['key'],array(FALSE=>"FALSE",TRUE=>"TRUE"), $forms['location']['value'], array(
					'placeholder' => "", 
					'class' => 'tips form-control', 
					'id' => $forms['location']['key'], 
					'data-original-title'=> __("Displays location select"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Displays the Select Location in the Ad form."),
					))?> 
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label($forms['price']['key'], __('Price'), array('class'=>'control-label col-sm-3', 'for'=>$forms['price']['key']))?>
				<div class="col-sm-4">
					<?= FORM::select($forms['price']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['price']['value'], array(
					'placeholder' => "", 
					'class' => 'tips form-control', 
					'id' => $forms['price']['key'], 
					'data-original-title'=> __("Price field"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Displays the field Price in the Ad form."),
					))?> 
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label($forms['upload_file']['key'], __('Upload file'), array('class'=>'control-label col-sm-3', 'for'=>$forms['upload_file']['key']))?>
				<div class="col-sm-4">
					<?= FORM::select($forms['upload_file']['key'],array(FALSE=>"FALSE",TRUE=>"TRUE"), $forms['upload_file']['value'], array(
					'placeholder' => "", 
					'class' => 'tips form-control', 
					'id' => $forms['upload_file']['key'], 
					))?>
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label($forms['captcha']['key'], __('Captcha'), array('class'=>'control-label col-sm-3', 'for'=>$forms['captcha']['key']))?>
				<div class="col-sm-4">
					<?= FORM::select($forms['captcha']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"), $forms['captcha']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips form-control', 
					'id' => $forms['captcha']['key'], 
					'data-original-title'=> __("Enables Captcha"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Captcha appears in the form."),
					))?> 
				</div>
			</div>
            <div class="form-group">
                <?= FORM::label($forms['contact']['key'], __('Contact form'), array('class'=>'control-label col-sm-3', 'for'=>$forms['contact']['key']))?>
                <div class="col-sm-4">
                    <?= FORM::select($forms['contact']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"), $forms['contact']['value'], array(
                    'placeholder' => "", 
                    'class' => 'tips form-control', 
                    'id' => $forms['contact']['key'], 
                    'data-original-title'=> __("Enables Contact Form"),
                    'data-trigger'=>"hover",
                    'data-placement'=>"right",
                    'data-toggle'=>"popover",
                    'data-content'=>__("Contact form appears int he ad."),
                    ))?> 
                </div>
            </div>
			<?$pages = array(''=>__('Deactivated'))?>
			<?foreach (Model_Content::get_pages() as $key => $value) {
				$pages[$value->seotitle] = $value->title;
			}?>
			<div class="form-group">
				<?= FORM::label($forms['tos']['key'], "<a target='_blank' href='http://open-classifieds.com/2013/08/13/how_to_add_pages/'>".__('Terms of Service')."</a>", array('class'=>'control-label col-sm-3', 'for'=>$forms['tos']['key']))?>
				<div class="col-sm-4">
					<?= FORM::select($forms['tos']['key'], $pages, $forms['tos']['value'], array(
					'placeholder' => "http://foo.com/", 
					'class' => 'tips form-control', 
					'id' => $forms['tos']['key'], 
					'data-content'=> __("If you choose to use terms of service, you can select activate. And to edit content, select link 'Content' on your admin panel sidebar. Find page named 'Terms of service' click 'Edit'. In section 'Description' add content that suits you."),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-original-title'=>__("Terms of Service"),
					))?> 
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label($forms['disqus']['key'], __('Disqus'), array('class'=>'control-label col-sm-3', 'for'=>$forms['disqus']['key']))?>
				<div class="col-sm-4">
					<?= FORM::input($forms['disqus']['key'], $forms['disqus']['value'], array(
					'placeholder' => "", 
					'class' => 'tips form-control', 
					'id' => $forms['disqus']['key'], 
					'data-original-title'=> __("Disqus Comments"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("You need to write your disqus ID to enable the service."),
					))?> 
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label($forms['banned_words']['key'], __('Banned words'), array('class'=>'control-label col-sm-3', 'for'=>$forms['banned_words']['key']))?>
				<div class="col-sm-4">
					<?= FORM::input($forms['banned_words']['key'], $forms['banned_words']['value'], array(
					'placeholder' => "word1,word2,word3", 
					'class' => 'tips form-control', 
					'id' => $forms['banned_words']['key'], 
					'data-original-title'=> __("Baned words are separated with coma (,)"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("You need to write your baned words enable the service."),
					))?> 
				</div>
			</div>
			<div class="form-group">
				<?= FORM::label($forms['banned_words_replacement']['key'], __('Banned words replacement'), array('class'=>'control-label col-sm-3', 'for'=>$forms['banned_words_replacement']['key']))?>
				<div class="col-sm-4">
					<?= FORM::input($forms['banned_words_replacement']['key'], $forms['banned_words_replacement']['value'], array(
					'placeholder' => "xxx", 
					'class' => 'tips form-control', 
					'id' => $forms['banned_words_replacement']['key'], 
					'data-original-title'=> __("Replacement of a banedword"),
					'data-trigger'=>"hover",
					'data-placement'=>"right",
					'data-toggle'=>"popover",
					'data-content'=>__("Baned word replacement replaces selected array with string that you provided."),
					))?> 
				</div>
			</div>
			
				<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'form'))))?>
			
		</fieldset>
	<?= FORM::close()?>
</div><!--end well-->
