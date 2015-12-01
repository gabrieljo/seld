<?php
echo form_open("u/create/design", array('class'=>'form-horizontal'));

if (isset($type)){
	include($page."-{$type}.php");
}

echo form_close();
?>