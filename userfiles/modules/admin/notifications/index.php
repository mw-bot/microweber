<?php
only_admin_access();
if(!isset($notif_params)){
$notif_params = $params;
}
if(isset($notif_params['id'])){
	unset($notif_params['id']);
}
if(isset($notif_params['module'])){
	unset($notif_params['module']);
}

/*if(isset($params['is_read'])){
	$notif_params["is_read"] = $params['is_read'];
}
if(isset($params['limit'])){
	$notif_params["is_read"] = $params['is_read'];
}*/


if(!isset($notif_params['limit'])){
	$notif_params["limit"] = 500;
}


$notif_params["order_by"] = 'created_on desc';
$notif_params["order_by"] = 'is_read desc, created_on desc';
 
$data = mw('Microweber\Notifications')->get($notif_params);
$wrapper_id = "admin_notifications";
if(isset($notif_params['wrapper-id'])){
$wrapper_id = $notif_params['wrapper-id'];
}
$is_quick = false;

if(isset($notif_params['quick'])){
$is_quick = $notif_params['quick'];
}

?>
<script  type="text/javascript">

mw.notif_item_read = function($item_id){
     	  $.get("<?php print api_link('Notifications/read'); ?>?id="+$item_id, function(){
  
    	  });
 
}
mw.notif_item_delete = function($item_id){
     mw.tools.confirm(mw.msg.del, function(){
    	  $.get("<?php print api_link('Notifications/delete'); ?>?id="+$item_id, function(){
    		 	//mw.$('.mw-ui-admin-notif-item-'+$item_id).fadeOut();
						 	mw.reload_module('admin/notifications');
							//mw.reload_module('#<?php print $params['id'] ?>');

    	  });
     });

}



 
mw.notif_reset_all = function(){
	 $.get("<?php print api_link('Notifications/reset'); ?>", function(){
		 	mw.reload_module('admin/notifications');
									//	mw.reload_module('#<?php print $params['id'] ?>');

	  });
}


mw.notif_mark_all_as_read = function(){
	 $.get("<?php print api_link('Notifications/mark_all_as_read'); ?>", function(){
		 	mw.reload_module('admin/notifications');
									//	mw.reload_module('#<?php print $params['id'] ?>');

	  });
}


</script>
<?php if(is_array($data )): ?>
<?php $periods = array("Today", "Yesterday", "This week", "This mount, Older"); ?>
<?php $periods_printed = array(); ?>
<?php
	/*		foreach($periods as $period){
				if(!in_array($period ,$periods_printed )){
					$time1 = strtotime($item['created_on']);


					$time2 = strtotime($period);

					if($time1 < $time2){
					 print 	$period;
					 $periods_printed[] = $period;
					}

				}

			}*/



			  ?>

<div class="mw-admin-notifications-holder" id="<?php print $wrapper_id ?>">
	<table cellspacing="0" cellpadding="0" class="mw-ui-admin-table">
		
		<?php if($is_quick == false): ?>
		<colgroup>
		<col width="40">
		<col width="200">
		<col width="auto">
		<col width="40">
		</colgroup>
		<thead>
			<tr valign="middle">
				<th colspan="4" valign="middle"><div class="left">
						<h2><span class="ico inotification"></span>
							<?php _e("Your Notifications"); ?>
						</h2>
					</div>
					<?php if($is_quick == false): ?>
					<div class="right"><a href="javascript:mw.notif_mark_all_as_read();" class="mw-ui-btn">Mark all as read</a></div>
					<?php endif; ?>
				</th>
			</tr>
		</thead>
		<?php endif; ?>

		<tbody>
			<?php foreach($data  as $item): ?>
			<tr class="mw-ui-admin-notif-item-<?php print $item['id'] ?> <?php if(isset($item['is_read']) and trim( $item['is_read']) == 'n'): ?>mw-success<?php endif; ?>" <?php if(isset($item['is_read']) and trim( $item['is_read']) == 'n'): ?> onclick="mw.notif_item_read('<?php print $item['id'] ?>');" <?php endif; ?>>
				<?php
  	    $mod_info = false;
  	    if(isset($item['module']) and $item['module'] != ''){
  		    $mod_info = module_info($item['module']);
  	    }
        ?>
				<td><?php if($mod_info != false and isset($mod_info['name'])): ?>
					<img src=" <?php   print thumbnail($mod_info['icon'], 16,16) ?>" />
					<?php endif; ?></td>
				<td><?php if($mod_info != false and isset($mod_info['name'])): ?>
					<a class="mw-ui-link" href="<?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($item['module']) ?>/mw_notif:<?php print $item['id'] ?>" title="<?php print $mod_info['name'] ?>"> <?php print $item['title'] ?></a>
					<?php elseif(isset($item['rel']) and $item['rel'] == 'content'): ?>
					<a class="mw-ui-link" href="<?php print admin_url() ?>view:content#action=editpage:<?php print ($item['rel_id']) ?>"> <?php print $item['title'] ?></a>
					<?php else : ?>
					<?php print $item['title'] ?>
					<?php endif; ?>
					
					
					<div class="notification_info">
			 
				 
					
					<time title="<?php print mw('format')->date($item['created_on']); ?>"><?php print mw('format')->ago($item['created_on'],1); ?></time>
					</div>
					
					
					</td>
				
				
				<?php if($is_quick == false): ?>
				<td style="max-width: 60%;"><div class="notification_info"><a href="<?php if($mod_info != false and isset($mod_info['name'])): ?><?php print admin_url() ?>view:modules/load_module:<?php print module_name_encode($item['module']) ?>/mw_notif:<?php  print  $item['id'] ?><?php endif; ?>" class="ellipsis">
						<?php if(isset($item['content']) and $item['content'] != ''): ?>
						<?php print $item['content']; ?>
						<?php else : ?>
						<?php endif; ?>
						</a></div></td>
				<?php endif; ?>		
						
						
				<?php if($is_quick == false): ?>		
				<td><a href="javascript:mw.notif_item_delete('<?php print $item['id'] ?>');" class="mw-ui-admin-table-show-on-hover mw-ui-btnclose"></a></td>
				<?php endif; ?>	
			</tr>
			<?php endforeach ; ?>
		</tbody>
	</table>
	<?php if($is_quick == false): ?>
	<div class="vSpace"></div>
	<div class="left">
	<a href="javascript:mw.notif_mark_all_as_read();" class="mw-ui-link">Read all</a>&nbsp;&nbsp;| 
	<a href="javascript:mw.notif_reset_all();" class="mw-ui-link">Unread all</a>&nbsp;&nbsp;| 
	&nbsp;&nbsp;<a href="javascript:mw.notif_item_delete('all');" class="mw-ui-link">Delete all</a>
	</div>
	
	
	<a class="mw-ui-btn right" href="javascript:mw.load_module('admin/notifications/system_log','#admin_notifications')">
	<?php _e("Show system log"); ?>
	</a>
	<?php endif; ?>
</div>
<?php else : ?>
<?php if($is_quick == false): ?>
<div class="mw-o-box" style="width: 500px;text-align: center;margin: 60px auto;">
	<div class="mw-o-box-header">
		<h2>
			<?php _e("No new notifications available"); ?>
			!</h2>
	</div>
	<div class="mw-o-box-content">
		<p>
			<?php _e("Choose your Action"); ?>
		</p>
		<br>
		<p> <a href="<?php print admin_url() ?>view:dashboard" class="mw-ui-btn mw-ui-btn-blue" style="margin-right: 12px;">
			<?php _e("Back to Dashboard"); ?>
			</a> <a href="<?php print admin_url() ?>view:content" class="mw-ui-btn mw-ui-btn-green">
			<?php _e("Manage your Content"); ?>
			</a> </p>
		<br>
		<?php //print notif('No new notifications available!'); ?>
	</div>
</div>
<?php endif; ?>
<?php endif; ?>
