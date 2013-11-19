<?php defined('SYSPATH') or die('No direct script access.');?>

<div class="page-header">
    <h1><?=__('System Logs')?></h1>
    <p><?=__('Reading log file')?><code> <?=$file?></code></p>
    <form id="" class="form-horizontal" method="get" action="">
        <fieldset>
	        <div class="form-group">
	        	<div class="col-sm-4">
	            	<input  type="text" class="form-control" size="16" id="date" name="date"  value="<?=$date?>" data-date-format="yyyy-mm-dd">
	        	</div>
	        </div>
            <button class="btn btn-primary"><?=__('Log')?></button>
        </fieldset>
    </form>
</div>

<textarea class="col-md-9 form-control" rows="20">
<?=$log?>
</textarea>