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
						<?=anchor('m/create', '디자인 <span class="glyphicon glyphicon-triangle-right"></span>', array('class'=>'btn btn-info btn-sm pull-right'))?>
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
						<?=anchor('m/designs', '목록보기 <span class="glyphicon glyphicon-triangle-right"></span>', array('class'=>'btn btn-info btn-sm pull-right'))?>					
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
						<?=anchor('m/profile', 'List <span class="glyphicon glyphicon-triangle-right"></span>', array('class'=>'btn btn-sm pull-right', 'style'=>'position:relative;top:-30px;color:#fff;'))?>
					</div>
				</li>
			</ul>
			<div class="cf"></div>
			<div class="row">
				<div class="col-sm-6">
					<h4 class="record-title">News</h4>
					<ul class="record-list">
						<?php
						foreach ($news->result() as $article){
							echo '<li>' . anchor('m/article/' . $article->art_id . '/' . url_title($article->art_title), $article->art_title) . '</li>';
						}
						?>
					</ul>
				</div>
				<div class="col-sm-6">
					<h4 class="record-title">Notice</h4>
					<ul class="record-list">
						<?php
						foreach ($notices->result() as $article){
							echo '<li>' . anchor('m/article/' . $article->art_id . '/' . url_title($article->art_title), $article->art_title) . '</li>';
						}
						?>
					</ul>
				</div>
			</div>
		</div>
		<div class="col-sm-4 leftbar">
			
			<div class="user-info">
				<div class="user-img pull-left">
					<span class="glyphicon glyphicon-user pull-left"></span>					
				</div>
				<div class="user-desc pull-left">
					<h4><?=sprintf('%s %s', $client->cl_firstname, $client->cl_lastname)?></h4>
					<?=$client->cl_company?><br>
					<?=anchor('m/profile', 'Edit Profile', array('class'=>'btn btn-xs btn-warning'))?>
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
			<?=anchor('m/logs', '<span class="glyphicon glyphicon-list"></span> View All', array('class'=>'pull-right btn btn-warning btn-xs'))?>
			<div class="cf"></div>
			<hr>

			<h4 class="record-title">Q&amp;A</h4>
			<ul class="record-list">
				<?php
				foreach ($qnas->result() as $article){
					echo '<li>' . anchor('m/article/' . $article->art_id . '/' . url_title($article->art_title), $article->art_title) . '</li>';
				}
				?>
			</ul>
		</div>
	</div>
</div> <!-- .container.row -->

<div class="container">
	<br />
	<div class="row">
		<div class="col-sm-6">
			<h2><span class="glyphicon glyphicon-eye-open"></span> My Watchlist</h2>
			<ul class="watchlsit">
				<?php
				foreach ($watchlist->result() as $item){
					echo '<li>';
					echo anchor('market/design/' . $item->pr_uid, ucfirst($item->pr_title));
					echo '<br />' . $item->pr_mk_description;
					echo '<br /> <small>Added to watchlist on: ' . date('jS M, Y g:iA', strtotime($item->fav_created_at)) . '</small>';
					echo '</li>';
				}
				?>
			</ul>
			<br>
			<?=anchor('m/watchlist', '<span class="glyphicon glyphicon-list"></span> View All', array('class'=>'btn btn-warning btn-xs'))?>
		</div>
		<div class="col-sm-6">
			<h2><span class="glyphicon glyphicon-barcode"></span> My Purchases</h2>
			<ul class="watchlsit">
				<?php
				foreach ($purchases->result() as $item){
					echo '<li>';
					echo anchor('m/purchase/' . $item->pc_uid, 'Total: ' . $item->pc_items . 'designs - Amount: <strong>₩' . number_format($item->pc_amount) . '</strong>');
					echo '<br /> <small>Purchased on: ' . date('jS M, Y g:iA', strtotime($item->pc_created_at)) . '</small>';
					echo '</li>';
				}
				?>
			</ul>
			<br>
			<?=anchor('m/purchases', '<span class="glyphicon glyphicon-list"></span> View All', array('class'=>'btn btn-warning btn-xs'))?>
		</div>
	</div>
</div>

<?php include('./application/views/inc/n_footer.php') ?>