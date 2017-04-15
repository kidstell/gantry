<?php
@include 'gantry.config';

!defined('_GANTRY_START')?die('config file not loaded'):null;
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="assets/css/bootstrap.min.css">
	<style type="text/css">
		.inline{
			display: inline;
		}
	</style>
</head>
<body>
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h2><a href="<?php echo $_SERVER['PHP_SELF'] ?>">_GANTRY</a></h2>
			<div class="well">
				<form>
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
						    <label for="folder">Folder URI (file path)</label>
						    <input type="text" id="folder" name="folder" class="form-control" placeholder="folder file path URL" value="<?php echo old('folder'); ?>">
						    <span class="help-block">always add asterix</span>
						  </div>
						</div>
						<div class="col-md-12">
							<div class="form-group">
						    <label for="exception_patterns">Exception patterns</label>
						    <textarea id="exception_patterns" name="exception_patterns" class="form-control" placeholder="comma or new line seperated values"><?php echo old('exception_patterns'); ?></textarea>
						  </div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
						    <label for="datetime">Modification benchmark(D-M-Y Hour:minute:second)</label>
						    <input type="text" id="datetime" name="datetime" class="form-control" placeholder="Date[Time]" value="<?php echo old('datetime') ?>">
						    <span class="help-block">Hour:minute:second is optional</span>
						  </div>
						</div>
						<div class="col-md-6">
						  <!-- <div class="form-group">
						    <label for="exampleInputPassword1">Password</label>
						    <input type="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
						  </div> -->
						</div>
					</div>
				  <button type="submit" id="submit" name="submit" class="btn btn-default">Submit</button>
				</form>
			</div>

			<div>
				<table id="lister" class="table table-stripped table-condensed" data-folder="<?php echo old('folder') ?>" data-benchmark="<?php echo old('datetime') ?>">
					<tr>
						<th>
							<div class='dropdown inline'>
							  <button class='btn btn-xs btn-link dropdown-toggle' type='button' id='".md5($key)."' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
							    <i class='glyphicon glyphicon-option-vertical'> </i>
							  </button>
							  <ul class='dropdown-menu' aria-labelledby='".md5($key)."'>
							    <li><a class='a-all' role='button'><i class='glyphicon glyphicon-check'> </i> Select All</a></li>
							    <li><a class='a-unall' role='button'><i class='glyphicon glyphicon-unchecked'> </i> Deselect All</a></li>
							  </ul>
							</div>
							<input type="checkbox" name="checkall" id="checkall">
						</th>
						<th>filename</th>
						<th>path</th>
						<th>last modified</th>
						<th>size</th>
					</tr>

					<?php $files = $listing['files']; $vol = 0; ?>
					<?php
						foreach ($files as $key => $value) {
							echo "<tr>
								<td>
									<input type='hidden' class='file-tick' value='{$key}' id='".md5($key)."'>
									<div class='dropdown inline'>
									  <button class='btn btn-xs btn-link dropdown-toggle' type='button' id='dropdown".md5($key)."' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>
									    <i class='glyphicon glyphicon-option-vertical'> </i>
									  </button>
									  <ul class='dropdown-menu' aria-labelledby='dropdown".md5($key)."'>
									    <li><a class='a-exempter' role='button'><i class='glyphicon glyphicon-scissors'> </i> Add to Exception</a></li>
									  </ul>
									</div>
								</td>
								<td colspan='4'>{$key}(".count($value).")</td></tr>";
							$vol+=count($value);
							if (!count($value)) {
								echo "No changed files since ";
							}else{
								foreach ($value as $index => $fn) {
					?>
					<tr>
						<td>
							<div class="dropdown inline">
							  <button class="btn btn-xs btn-link dropdown-toggle" type="button" id="dropdown<?php echo md5($key.'/'.$fn) ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
							    <i class="glyphicon glyphicon-option-vertical"> </i>
							  </button>
							  <ul class="dropdown-menu" aria-labelledby="dropdown<?php echo md5($key.'/'.$fn) ?>">
							    <li><a class="a-exempter" role="button"><i class="glyphicon glyphicon-scissors"> </i> Add to Exception</a></li>
							    <li><a class="a-copy" role="button"><i class="glyphicon glyphicon-copy"> </i> Copy</a></li>
							  </ul>
							</div>
							<input type="checkbox" name="check[]" id="<?php echo md5($key.'/'.$fn) ?>" value="<?php echo $key.'/'.$fn ?>" class="inline">
						</td>
						<td><?=$fn?></td>
						<td class="path" title="<?=$key.'/'.$fn?>"><a href="file:///<?=$key.'/'.$fn?>" target="_blank"><?='.../'.$fn?></a></td>
						<td><?=date('d-m-Y H:i:s', filemtime($key.'/'.$fn))?></td>
						<td><?php echo number_format(filesize($key.'/'.$fn)/1024, 2) ?>KB</td>
					</tr>
					<?php }}
					} ?>
					<tr>
						<td colspan="5"><?=$vol?> files changed in all</td>
					</tr>
					<tr>
						<td colspan="5"><button type="submit" id="copier" name="copier" class="btn btn-warning">Copy Selected</button></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div id="dd"></div>
  <script type="text/javascript" src="assets/js/jquery-2.2.4.min.js"></script>
  <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
	<script type="text/javascript">
		function serverside(pathCSV) {
			formdata = {};
  		formdata.rtype = 'ajax';
  		formdata.root = $('#lister').data('folder');
  		formdata.checks = pathCSV;
  		formdata.benchmark = $('#lister').data('benchmark');
			$.ajax({
          url: location.origin+location.pathname,
          type: 'GET',
          data: formdata,
          dataType:"json",
          success: function(response) {
          	if (response.status == 1 && response.message != '' && response.status!=undefined && response.message!=undefined) {
        			alert(response.message);
          	}
          },
          error: function(err) {
            $('#dd').html(err.responseText);
          }
      });
		}
		jQuery(document).ready(function() {
			$('#checkall').on('change',function(evt){
				state = $('#checkall')[0].checked;
				all = $("input[name='check[]']");
				$.each(all,function(i,item){
					item.checked = state;
				});
			});
			$('.a-unall').on('click',function(evt){
				$('#checkall')[0].checked = false;
				all = $("input[name='check[]']");
				$.each(all,function(i,item){
					item.checked = false;
				});
			});
			$('.a-all').on('click',function(evt){
				$('#checkall')[0].checked = true;
				all = $("input[name='check[]']");
				$.each(all,function(i,item){
					item.checked = true;
				});
			});
			$('.a-exempter').on('click', function(evt){
				fpath = $(this).parents('td:first').find('input[type="checkbox"], .file-tick').val();
				exceptions = $('#exception_patterns').val();
				set = exceptions.split(',');
				likeness = exceptions.indexOf(fpath);
				newset = []; found = false;
				for (var i = 0; i < set.length; i++) {
					if (fpath == set[i]) {
						found = true;
					}
				}
				if (found == false) {
					if (exceptions != '') {exceptions+=','; }
					exceptions+=fpath;
					$('#exception_patterns').val(exceptions);
				}
			});
			$('.a-copy').on('click', function(evt){
				fpath = $(this).parents('td:first').find('input[type="checkbox"]').val();
				serverside(fpath);
			});
    	$('#copier').on('click', function(evt){
    		evt.preventDefault();
    		checks='';
    		$set = $('input[name="check[]"]:checked');
    		$.each($set, function(i,item){
    			if (checks!='') checks+=',';
    			checks+=$(item).val();
    		});
    		if (checks == '') {
    			alert('you have selected any file(s) for copying');
    			return;
    		}
				serverside(checks);
    	});
    });
	</script>
</body>
</html>