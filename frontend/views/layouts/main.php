<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="Magz is a HTML5 & CSS3 magazine template is based on Bootstrap 3.">
		<meta name="author" content="Kodinger">
		<meta name="keyword" content="magz, html5, css3, template, magazine template">
		<!-- Shareable -->
		<meta property="og:title" content="HTML5 & CSS3 magazine template is based on Bootstrap 3" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content="http://github.com/nauvalazhar/Magz" />
		<meta property="og:image" content="https://raw.githubusercontent.com/nauvalazhar/Magz/master/images/preview.png" />
		<title>E-Serikat</title>
		<!-- Bootstrap -->
		<link rel="stylesheet" href="scripts/bootstrap/bootstrap.min.css">
		<!-- IonIcons -->
		<link rel="stylesheet" href="scripts/ionicons/css/ionicons.min.css">
		<!-- Toast -->
		<link rel="stylesheet" href="scripts/toast/jquery.toast.min.css">
		<!-- OwlCarousel -->
		<link rel="stylesheet" href="scripts/owlcarousel/dist/assets/owl.carousel.min.css">
		<link rel="stylesheet" href="scripts/owlcarousel/dist/assets/owl.theme.default.min.css">
		<!-- Magnific Popup -->
		<link rel="stylesheet" href="scripts/magnific-popup/dist/magnific-popup.css">
		<link rel="stylesheet" href="scripts/sweetalert/dist/sweetalert.css">
		<!-- Custom style -->
		<link rel="stylesheet" href="css/style.css">
		<link rel="stylesheet" href="css/custom.css">
		<link rel="stylesheet" href="css/skins/green.css">
	</head>

	<body class="skin-green">
		<header class="primary">
			<div class="firstbar">
				<div class="container">
					<div class="row">
						<div class="col-md-6 col-sm-12">
							<div class="brand">
								<a href="index.php">
									<img src="images/logo.png" alt="Magz Logo">
								</a>
							</div>
						</div>
						<div class="col-md-6 col-sm-12 text-right">
							<ul class="nav-icons">
								<li><a href="login.php"><i class="ion-person"></i><div>Login</div></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</header>

<section class="home">
	<div class="container">
		<div class="row">
			<!-- <div class="col-md-8 col-sm-12 col-xs-12">
				<div class="row content-box">
					<div class="main_title2">
						<h2 class="title-col">Latest Activity</h2>
					</div>
					<div class="body-col"> -->

            <?= $this->render('content.php',['content'=>$content]) ?>
						<!-- <article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img09.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">Duis aute irure dolor in reprehenderit in voluptate velit</a></h1>
									<div class="detail">
										<div class="category"><a href="category.html">Lifestyle</a></div>
										<div class="time">December 22, 2016</div>
									</div>
								</div>
							</div>
						</article> -->
					<!-- </div>
				</div>
			</div> -->
      <div class="col-xs-6 col-md-4 sidebar" id="sidebar">
				<div class="sidebar-title for-tablet">Sidebar</div>
				<aside class="side-box">
					<div class="main_title2">
						<h2 class="aside-title">Upcoming <a href="#" class="all">See All <i class="ion-ios-arrow-right"></i></a></h2>
					</div>
					<div class="aside-body">
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img07.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">Fusce ullamcorper elit at felis cursus suscipit</a></h1>
								</div>
							</div>
						</article>
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img14.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">Duis aute irure dolor in reprehenderit in voluptate velit</a></h1>
								</div>
							</div>
						</article>
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img09.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">Aliquam et metus convallis tincidunt velit ut rhoncus dolor</a></h1>
								</div>
							</div>
						</article>
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img11.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">dui augue facilisis lacus fringilla pulvinar massa felis quis velit</a></h1>
								</div>
							</div>
						</article>
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img06.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">neque est semper nulla, ac elementum risus quam a enim</a></h1>
								</div>
							</div>
						</article>
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img03.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">Morbi vitae nisl ac mi luctus aliquet a vitae libero</a></h1>
								</div>
							</div>
						</article>
					</div>
				</aside>
				<aside class="side-box">
					<div class="main_title2">
						<h2 class="aside-title">Feed <a href="#" class="all">See All <i class="ion-ios-arrow-right"></i></a></h2>
					</div>
					<div class="aside-body">
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img07.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">Fusce ullamcorper elit at felis cursus suscipit</a></h1>
								</div>
							</div>
						</article>
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img14.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">Duis aute irure dolor in reprehenderit in voluptate velit</a></h1>
								</div>
							</div>
						</article>
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img09.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">Aliquam et metus convallis tincidunt velit ut rhoncus dolor</a></h1>
								</div>
							</div>
						</article>
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img11.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">dui augue facilisis lacus fringilla pulvinar massa felis quis velit</a></h1>
								</div>
							</div>
						</article>
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img06.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">neque est semper nulla, ac elementum risus quam a enim</a></h1>
								</div>
							</div>
						</article>
						<article class="article-mini">
							<div class="inner">
								<figure>
									<a href="artikel.php">
										<img src="images/news/img03.jpg" alt="Sample Article">
									</a>
								</figure>
								<div class="padding">
									<h1><a href="artikel.php">Morbi vitae nisl ac mi luctus aliquet a vitae libero</a></h1>
								</div>
							</div>
						</article>
					</div>
				</aside>
			</div>
		</div>
	</div>
</section>
  <!-- Start footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
          <div class="copyright">
            COPYRIGHT &copy; E-Serikat. ALL RIGHT RESERVED. Developed by <a href="https://www.mamorasoft.com">Mamorasoft</a>
          </div>
      </div>
    </div>
  </footer>
  <!-- End Footer -->


  <!-- JS -->
  <script src="js/jquery.js"></script>
  <script src="js/jquery.migrate.js"></script>
  <script src="scripts/bootstrap/bootstrap.min.js"></script>
  <script>var $target_end=$(".best-of-the-week");</script>
  <script src="scripts/jquery-number/jquery.number.min.js"></script>
  <script src="scripts/owlcarousel/dist/owl.carousel.min.js"></script>
  <script src="scripts/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
  <script src="scripts/easescroll/jquery.easeScroll.js"></script>
  <script src="scripts/sweetalert/dist/sweetalert.min.js"></script>
  <script src="scripts/toast/jquery.toast.min.js"></script>
  <script src="js/e-magz.js"></script>
  <script src="js/custom.js"></script>
</body>
</html>
