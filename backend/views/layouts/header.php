<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini"></span><span class="logo-lg">E-Serikat</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="navbar-custom-menu">

            <ul class="nav navbar-nav">

                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="<?= Yii::$app->urlManager->createUrl(['/template/img/petro.png']) ?>" class="user-image" alt="User Image"/>
                        <?= Yii::$app->user->identity->username ?>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- User image -->
                        <li class="user-header">
                            <img src="<?= Yii::$app->urlManager->createUrl(['/template/img/petro.png']) ?>" class="img-circle"
                                 alt="User Image"/>
                            <p>Selamat Datang</p>
                            <p><?php echo Yii::$app->user->identity->roles->name_role ?>, <?= Yii::$app->user->identity->username ?></p>
                        </li>
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <?= Html::a(
                                    'Ubah Kata Sandi',
                                    ['/serikatinti/update-password', 'id' => Yii::$app->user->identity->id],
                                    ['class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                            <div class="pull-right">
                                <?= Html::a(
                                    'Sign out',
                                    ['/site/logout'],
                                    ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                ) ?>
                            </div>
                        </li>
                    </ul>
                </li>

            </ul>
        </div>
    </nav>
</header>

<?php
Modal::begin([
    'header' => 'Tambah Data Dokter',
    'id' => 'modal',
    'size' => 'modal-md',
]);
echo "<div id='modalContent'></div>";
Modal::end();
?>

<script>
    $('.modalButton').on('click', function () {
        $('#modal').modal('show')
        .find('#modalContent')
        .load($(this).attr('value'));
    });
</script>
