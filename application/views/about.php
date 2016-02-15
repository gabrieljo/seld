<?php include('inc/n_header.php') ?>

<div class="n_container">
	<div class="container">
		<div class="about-main-bg">
			<div class="about-main-group">
				<div id="about-main-title">Life is too short.</div>
				<div id="about-main-desc">SELD와 함께라면, 누구나 훌륭한 디자이너가 될 수 있습니다.</div>	
				<a id="video-play">
					<i class="glyphicon glyphicon-triangle-right"></i>
				</a>
				<a href="#" class="btn btn-primary"><i class="glyphicon glyphicon-picture"></i> SELD 디자인 하기</a>
				<a href="#" class="btn btn-primary"><i class="glyphicon glyphicon-list-alt"></i> SELD 테플릿 보기</a>
				<a href="#" class="btn btn-primary"><i class="glyphicon glyphicon-shopping-cart"></i> SELD 마켓 보기</a>
			</div>
		</div>
		<div class="about-contents">
			<div class="first-row">
				<div class="col-sm-6">
					<div id="title">SELD 템플릿</div>
					<div id="desc">
						<p>
							수백여가지의 템플릿으로 디자이너가 없어도 <br/>
							자신만의 독특한 디자인을 만들 수 있습니다.<br/>
							SELD에서 제공하는 템플릿과 이미지를 무료로<br/>
							사용하세요.
						</p>
					</div>
				</div>
				<div class="col-sm-6" style="text-align: center;">
					<?=inc('about/1-article.jpg', array('alt'=>'SELD 템플릿 이미지'))?>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="second-row">
				<div class="col-sm-6" style="text-align: center;"><?=inc('about/2-article.jpg', array('alt'=>'SELD 마켓 이미지'))?></div>
				<div class="col-sm-6">
					<div class="contents">
						<div id="title">SELD의 간편한 디자인</div>
						<div id="desc">
							<p>
								복잡하지 않는 간단한 절차, 손쉬운 디자인<br/>
								회원가입만 하면 언제 어디서든 나만의 디자인을.<br/>
								<br/>
							</p>
						</div>
					</div>
					<div class="contents">
						<div id="title">내 디자인을 마켓에</div>
						<div id="desc">
							<p>
								나만의 디자인을 마켓에 판매할수 있습니다.<br/>
								디자인을 마켓에 올려 판매 및 고용을 하여<br/>
								새로운 문화를 창출 해보세요.<br/>
							</p>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>		
		</div>
	</div>
</div>

<?php include('inc/n_footer.php') ?>