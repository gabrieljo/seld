<?php include('inc/n_header.php') ?>

<div class="user-page">
	<div class="container">
		<div>
		  <!-- Nav tabs -->
		  <ul class="main-tab-menu nav nav-tabs" role="tablist">
		    <li role="presentation" class="active"><a href="#home" aria-controls="home" role="tab" data-toggle="tab"><div class="circle"><i class="fa fa-home" style="color:#22c222"></i></div></a></li>
		    <li role="presentation"><a href="#profile" aria-controls="profile" role="tab" data-toggle="tab"><div class="circle"><i class="fa fa-user" style="color:#febe29"></i></div></a></li>
		    <li role="presentation"><a href="#messages" aria-controls="messages" role="tab" data-toggle="tab"><div class="circle"><i class="fa fa-gift" style="color:#3e5e9a"></i></div></a></li>
		    <li role="presentation"><a href="#settings" aria-controls="settings" role="tab" data-toggle="tab"><div class="circle"><i class="fa fa-commenting-o" style="color:#f1685e"></i></div></a></li>
		  </ul>

		  <!-- Tab panes -->
		  <div class="tab-content">
		    <div role="tabpanel" class="tab-pane active" id="home">
		    	<?php $this->load->view('partials/mypage_home')?>			    
		    </div>
		    <div role="tabpanel" class="tab-pane" id="profile">
		    	<?php $this->load->view('partials/mypage_profile')?>
		    </div>
		    <div role="tabpanel" class="tab-pane" id="messages">
		    	<?php $this->load->view('partials/mypage_market')?>
		    </div>
		    <div role="tabpanel" class="tab-pane" id="settings">...</div>
		  </div>

		</div>


	</div>
</div>

<?php include('inc/n_footer.php') ?>