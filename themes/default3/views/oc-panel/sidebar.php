<?php defined('SYSPATH') or die('No direct script access.');?>

<aside class="col-md-1 col-sm-1 col-xs-1 respon-left-panel">
    <div class="sidebar-nav">
        <div class="clearfix"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-1 col-sm-1 col-xs-1 respon-left-panel">
                    <div class="panel-group" id="accordion">
                    <? if($user->id_role==Model_Role::ROLE_ADMIN):?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"><span class="glyphicon glyphicon-th">
                                    </span> <span class="title-txt">Classifieds</span></a>
                                </h4>
                            </div>
                            <div id="collapseOne" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td><?=Theme::admin_link(__('Advertisements'),'ad','index','oc-panel','glyphicon glyphicon-align-right')?></td></tr>
                                        <? if(core::config('general.moderation') == 1 OR // moderation on  
                                              core::config('general.moderation') == 4 OR // email confiramtion with moderation
                                              core::config('general.moderation') == 5):  // payment with moderation?>
                                            <tr><td><?=Theme::admin_link(__('Moderation'),'ad','moderate','oc-panel','glyphicon glyphicon-ban-circle')?></td></tr>   
                                        <? endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Categories'),'category','index','oc-panel','glyphicon  glyphicon-tags')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Locations'),'location','index','oc-panel','glyphicon  glyphicon-map-marker')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Orders'), 'order','index','oc-panel','glyphicon  glyphicon-shopping-cart')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo"><span class="glyphicon glyphicon-folder-open">
                                    </span> <span class="title-txt"><?=__('Content')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseTwo" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <?if (core::config('general.blog')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Blog'), 'blog','index','oc-panel','glyphicon glyphicon-pencil')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Page'), 'content','list?type=page&locale_select='.core::config('i18n.locale'),'oc-panel','glyphicon glyphicon-file')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Email'), 'content','list?type=email&locale_select='.core::config('i18n.locale'),'oc-panel','glyphicon glyphicon-envelope')?></td></tr>
                                        <?if (core::config('general.faq')==1):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('FAQ'), 'content','list?type=help&locale_select='.core::config('i18n.locale'),'oc-panel',' glyphicon glyphicon-question-sign')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Translations'), 'translations','index','oc-panel','glyphicon glyphicon-globe')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Newsletters'), 'newsletter','index','oc-panel','glyphicon glyphicon-envelope')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour"><span class="glyphicon glyphicon-picture">
                                    </span> <span class="title-txt"><?=__('Appearance')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseFour" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Themes'), 'theme','index','oc-panel','glyphicon glyphicon-picture')?></td></tr>
                                        <?if (Theme::has_options()):?>
                                            <tr><td class="br"><?=Theme::admin_link(__('Theme Options'), 'theme','options','oc-panel','glyphicon  glyphicon-wrench')?></td></tr>
                                        <?endif?>
                                        <tr><td class="br"><?=Theme::admin_link(__('Widgets'), 'widget','index','oc-panel','glyphicon glyphicon-move')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Menu'), 'menu','index','oc-panel','glyphicon glyphicon-list')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Custom Fields'), 'fields','index','oc-panel','glyphicon  glyphicon-plus-sign')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Social Auth'), 'social','index','oc-panel','glyphicon glyphicon-thumbs-up')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?if ($user->has_access_to_any('settings,config')):?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseSettings"><span class="glyphicon glyphicon-wrench">
                                    </span> <span class="title-txt"><?=__('Settings')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseSettings" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('General'), 'settings','general')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Payment'), 'settings','payment')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Email'), 'settings','email')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Advertisement'), 'settings','form')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                        <?if ($user->has_access_to_any('user,role,access')):?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseUser"><span class="glyphicon glyphicon-user">
                                    </span> <span class="title-txt"><?=__('Users')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseUser" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table no-hide">
                                        <tr><td class="br"><?=Theme::admin_link(__('Users'),'user')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('User Roles'),'role')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Roles access'),'access')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                        <?if ($user->has_access_to_any('tools')):?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseTools"><span class="glyphicon glyphicon-filter">
                                    </span> <span class="title-txt"><?=__('Tools')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseTools" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Updates'), 'update','index')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Sitemap'), 'tools','sitemap')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Migration'), 'tools','migration')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Optimize'), 'tools','optimize')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Cache'), 'tools','cache')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Logs'), 'tools','logs')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Import tool'), 'tools','import_tool')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('PHP Info'), 'tools','phpinfo')?></td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                    <?endif?>
                        <? if($user->has_access_to_any('profile') AND $user->id_role!=Model_Role::ROLE_ADMIN):?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive"><span class="glyphicon glyphicon-align-justify">
                                    </span> <span class="title-txt"><?=__('Profile Options')?></span></a>
                                </h4>
                            </div>
                            <div id="collapseFive" class="panel-collapse collapse">
                                <div class="panel-body">
                                    <table class="table">
                                        <tr><td class="br"><?=Theme::admin_link(__('Edit profile'), 'profile','edit','oc-panel','glyphicon glyphicon-edit')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('My Advertisements'), 'profile','ads','oc-panel','glyphicon glyphicon-bullhorn')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Stats'),'profile','stats','oc-panel','glyphicon glyphicon-align-left')?></td></tr>
                                        <tr><td class="br"><?=Theme::admin_link(__('Subscriptions'),'profile','subscriptions','oc-panel','glyphicon glyphicon-envelope')?></td></tr>
                                        <tr><td>
                                            <li>
                                                <a href="<?=Route::url('profile',array('seoname'=>$user->seoname))?>">
                                                    <i class="glyphicon glyphicon-user"></i>
                                                    <span class="side-name-link"><?=__('Public profile')?></span>
                                                </a>
                                            </li>
                                        </td></tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?endif?>
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <a  class=" btn-colapse-sidebar"><span class="glyphicon glyphicon-circle-arrow-left"></span>
                                    <span class="title-txt"><?=__('Collapse menu')?></span>
                                    </a>
                                </h4>
                            </div>
                        </div>
                        <?if (Theme::get('premium')!=1 AND Auth::instance()->get_user()->id_role == Model_Role::ROLE_ADMIN):?>
                        <div class="panel panel-default no-prem">
                            <div class="panel-heading panel-adv-class">
                                <a href="http://open-classifieds.com/?utm_source=<?=URL::base()?>&utm_medium=oc_sidebar&utm_campaign=<?=date('Y-m-d')?>"><span class="side-name-link">Open Classifieds</span></a>
                                <script type="text/javascript">
                                    (function() {var uid = Math.round(Math.random()*10000);
                                    document.write("<div id=\"serum_"+uid+"\" style=\"min-width:200px;min-height:200px;\" ></div>");
                                    var as= document.createElement("script"); as.type  = "text/javascript"; as.async = true;
                                    as.src= (document.location.protocol == "https:" ? "https" : "http")+ "://api.adserum.com/async.js?id="+uid+"&a=6&f=3&w=200&h=200";
                                    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(as, s);})();
                                </script>
                                <a class="no-prem" href="http://open-classifieds.com/2013/08/19/can-i-remove-license/"><span class="side-name-link"><?=__('How to remove this')?>?</span></a>
                                <a href="https://twitter.com/openclassifieds"
                                   onclick="javascript:_gaq.push(['_trackEvent','outbound-widget','http://twitter.com']);"
                                   class="twitter-follow-button" data-show-count="false"
                                   data-size="large">Follow @openclassifieds</a><br />
                                <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></li>
                            </div>
                        </div>
                        <?endif?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</aside>
