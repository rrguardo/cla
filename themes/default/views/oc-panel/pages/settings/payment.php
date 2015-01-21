<?php defined('SYSPATH') or die('No direct script access.');?>

	
		 <?=Form::errors()?>
		<div class="page-header">
			<h1><?=__('Payments Configuration')?></h1>
            <p class=""><?=__('List of payment configuration values. Replace input fields with new desired values.')?></p>

		</div>


		<div class="well">
		<?= FORM::open(Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment')), array('class'=>'form-horizontal', 'enctype'=>'multipart/form-data'))?>
			<fieldset>
				<?foreach ($config as $c):?>
					<?$forms[$c->config_key] = array('key'=>$c->config_key, 'value'=>$c->config_value)?>
				<?endforeach?>

                <div class="form-group">
                    <?= FORM::label($forms['paypal_account']['key'], __('Paypal account'), array('class'=>'control-label col-sm-3', 'for'=>$forms['paypal_account']['key']))?>
                    <div class="col-sm-4">
                        <?= FORM::input($forms['paypal_account']['key'], $forms['paypal_account']['value'], array(
                        'placeholder' => "some@email.com", 
                        'class' => 'tips form-control', 
                        'id' => $forms['paypal_account']['key'],
                        'data-original-title'=> __("Paypal mail address"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-content'=>__("The paypal email address where the payments will be sent"), 
                        ))?> 
                        </div>
                </div>

				<div class="form-group">
					<?= FORM::label($forms['sandbox']['key'], __('Sandbox'), array('class'=>'control-label col-sm-3', 'for'=>$forms['sandbox']['key']))?>
					<div class="col-sm-4">
						<?= FORM::select($forms['sandbox']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['sandbox']['value'], array(
						'placeholder' => "TRUE or FALSE", 
						'class' => 'tips form-controlti', 
						'id' => $forms['sandbox']['key'],
						'data-content'=> '',
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>'', 
						))?> 
					</div>
				</div>
				<div class="form-group">
					<?= FORM::label($forms['paypal_currency']['key'], __('Paypal currency'), array('class'=>'control-label col-sm-3', 'for'=>$forms['paypal_currency']['key']))?>
					<div class="col-sm-4">
						<?= FORM::select($forms['paypal_currency']['key'], $paypal_currency , array_search($forms['paypal_currency']['value'], $paypal_currency), array(
						'placeholder' => "USD", 
						'class' => 'tips form-control', 
						'id' => $forms['paypal_currency']['key'], 
						'data-original-title'=> __("Currency"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-content'=>__("Please be sure you are using a currency that paypal supports."),
						))?> 
					</div>
				</div>
				<div class="form-group">
					<?= FORM::label($forms['to_featured']['key'], __('Featured Ads'), array('class'=>'control-label col-sm-3', 'for'=>$forms['to_featured']['key']))?>
					<div class="col-sm-4">
						<?= FORM::select($forms['to_featured']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE") ,$forms['to_featured']['value'], array(
						'placeholder' => '', 
						'class' => 'tips form-control', 
						'id' => $forms['to_featured']['key'],
						'data-original-title'=> __("Featured ads"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-content'=>__("Featured ads will be highlighted for a defined number of days."), 

						))?> 
					</div>
				</div>
                <div class="form-group">
                    <?= FORM::label($forms['pay_to_go_on_feature']['key'], __('Price for featuring the Ad'), array('class'=>'control-label col-sm-3', 'for'=>$forms['pay_to_go_on_feature']['key']))?>
                    <div class="col-sm-4">
                        <div class="input-group">
                        
                            <?= FORM::input($forms['pay_to_go_on_feature']['key'], $forms['pay_to_go_on_feature']['value'], array(
                            'placeholder' => "", 
                            'class' => 'tips form-control col-sm-3', 
                            'id' => $forms['pay_to_go_on_feature']['key'],
                            'data-original-title'=> __("Pricing"),
                            'data-trigger'=>"hover",
                            'data-placement'=>"right",
                            'data-toggle'=>"popover",
                            'data-content'=>__("How much the user needs to pay to feature an Ad"),  
                            ));?> 
                        
                        <span class="input-group-addon"><?=core::config('payment.paypal_currency')?></span></div>
                    </div>
                </div>
                <div class="form-group">
                <?= FORM::label($forms['featured_days']['key'], __('Days Featured'), array('class'=>'control-label col-sm-3', 'for'=>$forms['featured_days']['key']))?>
                <div class="col-sm-4">
                    <div class="input-group">
                        <?= FORM::input($forms['featured_days']['key'], $forms['featured_days']['value'], array(
                        'placeholder' => $forms['featured_days']['value'], 
                        'class' => 'tips form-control col-sm-3', 
                        'id' => $forms['featured_days']['key'], 
                        'data-original-title'=> __("Featured length"),
                        'data-trigger'=>"hover",
                        'data-placement'=>"right",
                        'data-toggle'=>"popover",
                        'data-content'=>__("How many days an ad will be featured after paying."),
                        ));?>
                        <span class="input-group-addon"><?=__("Days")?></span>
                    </div> 
                </div>
            </div>
				<div class="form-group">
					<?= FORM::label($forms['to_top']['key'], __('Bring to top Ad'), array('class'=>'control-label col-sm-3', 'for'=>$forms['to_top']['key']))?>
					<div class="col-sm-4">
						<?= FORM::select($forms['to_top']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE") ,$forms['to_top']['value'], array(
						'placeholder' => "", 
						'class' => 'tips form-control', 
						'id' => $forms['to_top']['key'], 
						'data-original-title'=> __("Bring to top Ad"),
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-content'=>__("Brings your Ad to the top of the listing."), 
						))?> 
					</div>
				</div>
				<div class="form-group">
					<?= FORM::label($forms['pay_to_go_on_top']['key'], __('To top price'), array('class'=>'control-label col-sm-3', 'for'=>$forms['pay_to_go_on_top']['key']))?>
					<div class="col-sm-4">
						<div class="input-group">
							<?= FORM::input($forms['pay_to_go_on_top']['key'], $forms['pay_to_go_on_top']['value'], array(
							'placeholder' => "", 
							'class' => 'tips form-control col-sm-3', 
							'id' => $forms['pay_to_go_on_top']['key'],
							'data-original-title'=> __("Pricing"),
							'data-trigger'=>"hover",
							'data-placement'=>"right",
							'data-toggle'=>"popover",
							'data-content'=>__("How much the user needs to pay to top up an Ad"),  
							));?> 
								<span class="input-group-addon"><?=core::config('payment.paypal_currency')?></span>
						</div>
					</div>
				</div>
				<div class="form-group">
					<?= FORM::label($forms['paypal_seller']['key'], "<a target='_blank' href='http://open-classifieds.com/2013/09/02/pay-directly-from-ad/'>".__('User paypal link')."</a>", array('class'=>'control-label col-sm-3', 'for'=>$forms['paypal_seller']['key']))?>
					<div class="col-sm-4">
						<?= FORM::select($forms['paypal_seller']['key'], array(FALSE=>"FALSE",TRUE=>"TRUE"),$forms['paypal_seller']['value'], array(
						'placeholder' => "TRUE or FALSE", 
						'class' => 'tips form-controlti', 
						'id' => $forms['paypal_seller']['key'],
						'data-content'=> '',
						'data-trigger'=>"hover",
						'data-placement'=>"right",
						'data-toggle'=>"popover",
						'data-original-title'=>'', 
						))?> 
					</div>
				</div>		

				
					<?= FORM::button('submit', 'Update', array('type'=>'submit', 'class'=>'btn btn-primary', 'action'=>Route::url('oc-panel',array('controller'=>'settings', 'action'=>'payment'))))?>
				
			</fieldset>	
	</div><!--end col-md-10-->
