<?php include('./application/views/inc/n_header.php') ?>

<div class="container seldMemberWrapper">
	<div class="row">
		<div class="col-sm-12">
			
		</div>
	</div><!-- member navigation -->

	<div class="row">
		<div class="col-sm-8">
			<ul id="main-links">
				<li>
					<div class="wrapper">
						<div class="link-title">User Create New Design.</div>
						<div class="link-desc">누구나 손쉽게 디자인<br>할 수 있는 SELD를<br>사용해보세요</div>
						<?=anchor('m/create', '디차인 <span class="glyphicon glyphicon-triangle-right"></span>', array('class'=>'btn btn-info btn-sm pull-right'))?>
					</div>
				</li>
				<li>
					<div class="wrapper">
						<div class="link-desc">주문한 디자인</div>
					</div>
				</li>
				<li>
					<div class="wrapper">
						<div class="link-desc">저장한 디자인</div>
						<?=anchor('m/designs', 'List <span class="glyphicon glyphicon-triangle-right"></span>', array('class'=>'btn btn-info btn-sm pull-right'))?>					
					</div>
				</li>
				<li>
					<div class="wrapper bg-shipping">
						<div class="link-desc">배송정보</div>
					</div>
				</li>
				<li>
					<div class="wrapper bg-lock">
						<div class="link-desc">비밀번호 변경 알림</div>
						<div class="link-title">회원님의 아이디 nebby은<br>비밀번호 변경 대상입니다.</div>
					</div>
				</li>
			</ul>
			<div class="cf"></div>
		</div>
		<div class="col-sm-4 leftbar">
			
			<div class="user-info">
				<div class="user-img pull-left">
					<span class="glyphicon glyphicon-user pull-left"></span>					
				</div>
				<div class="user-desc pull-left">
					<h4>gabriel jo</h4>
					<?=anchor('m/profile', 'Edit Profile', array('class'=>'btn btn-xs btn-default'))?>
					<br>Nice guy
				</div>
				<div class="cf"></div>
			</div>
			<hr>

			<h4>Recent activity</h4>
			<ul id="logs">
				<?php
				foreach ($logs->result() as $log){
					echo '<li>';
					echo $log->log_title;
					echo $log->log_type != 'session' && $log->log_ref != '' ? anchor($log->log_ref, 'Click to check ' . ucfirst($log->log_type)) . '.' : '';
					echo date(' jS M, Y H:iA', strtotime($log->log_created_at));
					echo '</li>';
				}
				?>
			</ul>
			<hr>

			<h4>Q&amp;A</h4>
		</div>
	</div>
</div> <!-- .container.row -->

<?php include('./application/views/inc/n_footer.php') ?>