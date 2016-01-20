<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>form</title>
	<style>
	*{font:12px Arial;}
	body{padding: 4em;}
	h1, h2, h3{font:bold 16px Verdana;}
	h2{color:red;}
	h3{color:green;}
	label{display: inline-block;width:120px;margin:10px 0;}
	.left,.right{float:left;width:70%;}
	.right{float:right;width:30%;}
	.container{background: #eee;border: 1px solid #ccc;height:85vh;overflow: auto;}
	a{padding:2px 0;display: block;text-decoration: none;color:blue;}
	a:hover{text-decoration: underline;font-size:120%;}
	a.new{border:1px solid #ccc; background: #fefefe; padding: 2px;text-align: center;padding: 10px;font-size: 120%;font-weight: bold;border-radius:4px;float:left;margin-right:10px;background:#eee;}
	a.new:hover{background:#ccc;}
	li.current a{color:#f00;font-size:120%;font-weight: bold;}
	small{color:#00f;background: #ff0;}
	i{color:#f00;}
	</style>
</head>
<body>
<div class="left">
	<?=anchor('a/opt', 'Add New Option', array('class'=>'new'))?>
	<h1>Options Editor <small><?=$uid?></small></h1>
	<?=$msg?>
	<?=form_open('a/opt/view/' . $uid)?>
	<?php
	$options = array();
	foreach ($designs->result() as $d){
		$options[''.$d->d_pr_id] = $d->d_pr_name;
	}
	?>
	<label>Heading:</label> <?=form_input('attr', $f['d_op_attr'])?> <br>
	<label>Product ID</label> <?=form_dropdown('pr_id', $options, $f['d_op_pr_id'])?> <br>
	<label>Column #: </label> <?=form_dropdown('cols', array('1'=>'1', '2'=>'2', '3'=>'3'), $f['d_op_col'])?> <br>
	<hr>
	<label>Title:</label> <?=form_input('title', $f['d_op_title'])?> <br>
	<label>Name:</label> <?=form_input('name', $f['d_op_name'])?> <br>
	<?php
	$options = '';
	if ($f['d_op_options'] != ''){
		$opts = unserialize($f['d_op_options']);
		foreach ($opts as $k=>$v){
			$options.= $k . "\n";
		}
	}
	?>
	<label>Options:</label> <?=form_textarea(array('name'=>'options', 'value'=>$options, 'rows'=>'22'))?> <br>
	<label>default:</label> <?=form_input('default', $f['d_op_default'])?> <br>
	<?php
	/*
	$type = 'default';
	if ($f['d_op_load'] != ''){
		$ty = unserialize($f['d_op_load']);
		$type = $ty['type'];
	}
	?>
	<label>Type:</label> <?=form_dropdown('type', array('default'=>'Default', 'ajax'=>'Dynamic'), $type)?> <br>

	<?php
	*/
	$list = array('0'=>'-- Select --');
	foreach ($items->result() as $item){
		$list[''.$item->d_op_id] = '[' . $item->d_op_id . '] ' . $item->d_op_title . ' -- ' . $item->d_op_default;
	}
	?>
	<label>Dependency: </label> <?=form_dropdown('depid', $list, $f['d_op_dep_id'])?> <br>
	<label>Dep Value:</label> <?=form_input('depname', $f['d_op_dep_val'])?> <br>
	<label></label>	<input type="submit" value="Save">
	<?=form_close()?>
</div>
<div class="right">
	<div class="container">
		<ul id="mylist">
			<?php
			foreach ($items->result() as $item){
				$cls = $uid == $item->d_op_uid ? 'current' : '';
				$extra = $item->d_op_dep_id != '0' ? ' -- <i> Dep ID: ' . $item->d_op_dep_id . ' </i> "' . $item->d_op_dep_val . '"' : $item->d_op_title;
				$prefix = $item->d_op_title == '' ? '&nbsp;&nbsp;-- ' : '';
				echo '<li class="' . $cls . '">' . anchor('a/opt/view/' . $item->d_op_uid, $prefix . '[id: ' . $item->d_op_id . '] ' . $extra) . '</li>';
			}
			?>
		</ul>
	</div>
</div>
</body>
</html>