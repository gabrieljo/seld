<?php
// All Steps
$steps = array(
			'product' 	=> '<span class="glyphicon glyphicon-blackboard"></span> <span class="tools-menu-title">Product</span>',
			'theme'		=> '<span class="glyphicon glyphicon-list"></span> <span class="tools-menu-title">Theme</span>',
			'settings' 	=> '<span class="glyphicon glyphicon-scissors"></span> <span class="tools-menu-title">Settings</span>',
			'design' 	=> '<span class="glyphicon glyphicon-pencil"></span><span class="tools-menu-title"> Custom</span>',
			'preview' 	=> '<span class="glyphicon glyphicon-eye-open"></span> <span class="tools-menu-title">Preview</span>',
			'payment'	=> '<span class="glyphicon glyphicon-barcode"></span> <span class="tools-menu-title">Payment</span>'
	);
?>
<div id="tools">
	<div id="tools-nav-wrapper">
		<ul>
			<?php
			$current = $this->uri->segment(3) == '' ? 'product' : $this->uri->segment(3);
			$breadcrumb = true;
			foreach ($steps as $k=>$v){
				$cls 	= $k == $current ? 'active' : '';
				$breadcrumb = $cls=='active' ? false : $breadcrumb;
				//echo '<li>'. anchor('u/create/' . $k, $v, array('class'=>$cls)) . '</li>';
				$href 	= $breadcrumb == true ? 'href="'. base_url() . 'u/create/' . $k . '"' : '';
				$extra 	= $href == '' ? 'disabled' : 'enabled';
				echo '<li><a ' . $href . ' class="'.$cls.' ' . $extra . '">'.$v.'</a></li>';
			}
			?>
		</ul>
	</div>
</div>