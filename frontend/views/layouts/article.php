<?php
use yii\helpers\Url;
use frontend\assets\AppAsset;

AppAsset::register($this);

 ?>

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
 		<link rel="stylesheet" href="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/bootstrap/bootstrap.min.css'])?>">
 		<!-- IonIcons -->
 		<link rel="stylesheet" href="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/ionicons/css/ionicons.min.css'])?>">
 		<!-- Toast -->
 		<link rel="stylesheet" href="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/toast/jquery.toast.min.css'])?>">
 		<!-- OwlCarousel -->
 		<link rel="stylesheet" href="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/owlcarousel/dist/assets/owl.carousel.min.css'])?>">
 		<link rel="stylesheet" href="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/owlcarousel/dist/assets/owl.theme.default.min.css'])?>">
 		<!-- Magnific Popup -->
 		<link rel="stylesheet" href="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/magnific-popup/dist/magnific-popup.css'])?>">
 		<link rel="stylesheet" href="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/sweetalert/dist/sweetalert.css'])?>">
 		<!-- Custom style -->
 		<link rel="stylesheet" href="<?=Yii::$app->urlManagerFrontend->createUrl(['/css/style.css'])?>">
 		<link rel="stylesheet" href="<?=Yii::$app->urlManagerFrontend->createUrl(['/css/custom.css'])?>">
 		<link rel="stylesheet" href="<?=Yii::$app->urlManagerFrontend->createUrl(['/css/skins/green.css'])?>">
 	</head>

<body class="skin-green">
  <header class="primary">
    <div class="firstbar">
      <div class="container">
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="brand">
              <a href="<?php echo Yii::$app->homeUrl; ?>">
                <img src="<?=Yii::$app->urlManagerFrontend->createUrl(['/images/logo.png'])?>" alt="Magz Logo">
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
  			<div class="col-md-8 col-sm-12 col-xs-12">
  				<div class="row content-box">
  					<div class="main_title2">
  						<h2 class="title-col">Artikel</h2>
  					</div>

            <?= $this->render('content.php',['content'=>$content]) ?>

  				</div>
  			</div>
        <div class="col-xs-6 col-md-4 sidebar" id="sidebar">
          <div class="sidebar-title for-tablet">Sidebar</div>
          <aside class="side-box">
            <div class="main_title2">
              <h2 class="aside-title">Kegiatan <a href="#" class="all">See All <i class="ion-ios-arrow-right"></i></a></h2>
            </div>
            <div class="aside-body">
              <?php
                  $allActivity = \common\models\Activity::find()->where(['done'=>1])->all();
                  foreach ($allActivity as $key => $activity) { ?>
                    <article class="article-mini">
                      <div class="inner">
                        <figure>
                          <a href="<?= Url::to(['kegiatan/detail','id'=>$activity->id])?>">
                            <?php $getRespo = \common\models\ActivityResponsibility::find()->where(['activity_id'=>$activity->id])->one() ?>
                            <img src="<?=Yii::$app->urlManagerBackend->createUrl(['/template/'.$getRespo->photo])?>" alt="Sample Article">
                          </a>
                        </figure>
                        <div class="padding">
                          <h1><a href="<?= Url::to(['kegiatan/detail','id'=>$activity->id])?>"><?php echo $activity->title ?></a></h1>
                        </div>
                      </div>
                    </article>
                  <?php }
               ?>
            </div>
          </aside>
          <aside class="side-box">
            <div class="main_title2">
              <h2 class="aside-title">Kegiatan Rutin <a href="#" class="all">See All <i class="ion-ios-arrow-right"></i></a></h2>
            </div>
            <div class="aside-body">
              <?php
                  $allActivity = \common\models\ActivityDaily::find()->where(['done'=>1])->all();
                  foreach ($allActivity as $key => $activity) { ?>
                    <?php $getRespo = \common\models\ActivityDailyResponsibility::find()->where(['activity_id'=>$activity->id])->one() ?>
                    <article class="article-mini">
                      <div class="inner">
                        <figure>
                          <a href="<?= Url::to(['kegiatan-rutin/detail','id'=>$activity->id])?>">
                            <img src="<?=Yii::$app->urlManagerBackend->createUrl(['/template/'.$getRespo->photo])?>" alt="Sample Article">
                          </a>
                        </figure>
                        <div class="padding">
                          <h1><a href="<?= Url::to(['kegiatan-rutin/detail','id'=>$activity->id])?>"><?php echo $activity->title ?></a></h1>
                        </div>
                      </div>
                    </article>
                  <?php }
               ?>
            </div>
          </aside>
        </div>		</div>
  	</div>
  </section>
  <!-- JS -->
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/js/jquery.js'])?>"></script>
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/js/jquery.migrate.js'])?>"></script>
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/bootstrap/bootstrap.min.js'])?>"></script>
  <script>var $target_end=$(".best-of-the-week");</script>
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/jquery-number/jquery.number.min.js'])?>"></script>
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/owlcarousel/dist/owl.carousel.min.js'])?>"></script>
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/magnific-popup/dist/jquery.magnific-popup.min.js'])?>"></script>
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/easescroll/jquery.easeScroll.js'])?>"></script>
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/sweetalert/dist/sweetalert.min.js'])?>"></script>
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/scripts/toast/jquery.toast.min.js'])?>"></script>
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/js/e-magz.js'])?>"></script>
  <script src="<?=Yii::$app->urlManagerFrontend->createUrl(['/js/custom.js'])?>"></script>

</body>
</html>
