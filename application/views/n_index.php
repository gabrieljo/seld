<?php include('inc/n_header.php') ?>

<div class="container">
	<div class="row">
		<div class="col-xs-12">
			<div class="n_slider">
				<div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
					<!-- Indicators -->
					<ol class="carousel-indicators">
						<li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
						<li data-target="#carousel-example-generic" data-slide-to="1"></li>
					</ol>

					<!-- Wrapper for slides -->
					<div class="carousel-inner" role="listbox">
						<div class="item active">
							<?=inc('n/s1.jpg')?>
							<div class="carousel-caption">
								<div class="caption-title">혼자 만드는 디자인</div>
								<div class="caption-content">SELD? Self Design!</div>
								<div class="caption-desc">디자이너가 없어도 디자인을, 내마음대로 편집하는 SELD</div>
							</div>
						</div>
						<div class="item">
							<?=inc('n/s1.jpg')?>
							<div class="carousel-caption">
							...
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="n_container">
	<div class="container">
		<div class="col-sm-2">
			<div class="sub-title">BEST REVIEW</div>
			<div class="best-review-item">
				<?=inc('example/best-review.jpg')?>
				<div class="item-detail">
					<div class="star-point"></div>
					<div class="item-title">디자인팝업 이벤트</div>
					<div class="item-desc">Design Popup events. description</div>
					<div class="item-price">88,000</div>
				</div>
			</div>
		</div>
		<div class="col-sm-7">
			<div class="sub-title">HOT ITEM</div>
			<div class="hot-itmes">
				<div class="hot-item-first pull-left">
					<?=inc('main/corner.png', array('class'=>'ribbon'))?>
					<div class="item-detail">
						<div class="item-title">디자인팝업 이벤트</div>
						<div class="item-desc">Design Popup events. description</div>
						<div class="item-price">88,000</div>
						<?=inc('example/hot-item-1.jpg')?>
					</div>
				</div>
				<div class="hot-item-others pull-left">
					<div class="row">
						<div class="hot-item-second">
							<?=inc('example/hot-2.jpg')?>
							<div class="item-detail">
								<div class="item-title">디자인팝업 이벤트</div>
								<div class="item-desc">Design Popup events. description</div>
								<div class="item-price">88,000</div>	
							</div>
							<div class="clearfix"></div>
						</div>
					</div> 
					<div class="row">
						<div class="hot-item-third">
							<?=inc('example/hot-3.jpg')?>
							<div class="item-detail">
								<div class="item-title">디자인팝업 이벤트</div>
								<div class="item-desc">Design Popup events. description</div>
								<div class="item-price">88,000</div>	
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
			<div class="clearfix"></div>
		</div>

		<div class="col-sm-3">
			<div class="sub-title">BEST ITEM</div>
			<div class="best-item">
				<div class="item-detail">
					<div class="item-title">디자인팝업 이벤트</div>
					<div class="item-desc">Design Popup events. description</div>
					<div class="item-price">88,000</div>
				</div>
				
			</div>

		</div>
	</div>

	<div class="container">
		<div class="seld-menu">
			<div class="sub-menu-title"> <span class="main-color" syle="color:#b7dcf0">SELD</span> 디자인 메뉴</div>
			<div class="menu-btns">
				<div class="col-xs-4">
					<a href="#">
						<div class="menu-btn seld-template">
							<?=inc('example/seld-design-1.jpg')?>
							<div class="menu-detail">
								<div class="menu-title">SELD 템플릿 보기</div>
								<div class="menu-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-xs-4">
					<a href="#">
						<div class="menu-btn seld-upload">
							<?=inc('example/seld-design-2.jpg')?>
							<div class="menu-detail">
								<div class="menu-title">SELD 마켓 보기</div>
								<div class="menu-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</div>
							</div>
						</div>
					</a>
				</div>
				<div class="col-xs-4">
					<a href="#">
						<div class="menu-btn seld-market">
							<?=inc('example/seld-design-3.jpg')?>
							<div class="menu-detail">
								<div class="menu-title">업로드 디자인 보기</div>
								<div class="menu-desc">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's</div>
							</div>
						</div>
					</a>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>

</div>

<?php include('inc/n_footer.php') ?>