<div class="body-col">
  <div>
    <img src="<?=Yii::$app->urlManagerBackend->createUrl(['/template'.$model->activityResponsibilities[0]['photo']])?>" style="width: 100%; height: 450px; object-fit: cover; border-radius: 3px;">
  </div>
  <article style="margin-top: 20px;">
    <h5 class="text-white"><?php echo $model->title ?></h5>
    <p class="text-white">

      <b>Gresik</b> - Kegiatan <?php echo $model->name_activity ?> ini, bertempat di <?php echo $model->place_activity ?> telah dilaksanakan dengan latar
      belakang <?php echo $model->background ?>. Dengan tujuan <?php echo $model->purpose ?>.
      Target kegiatan ini adalah <?php echo $model->target_activity ?>. Kegiatan ini diadakan pada tanggal <?php echo $model->date_start ?>,
      dan selesai pada tanggal <?php echo $model->date_end ?>.

    </p>
    <div class="detail">
      <div class="time"><?php echo date('F j, Y',strtotime($model->date_end));  ?></div>
      <div class="category"><a href="category.html"><?php echo $model->role0->name_role ?></a></div>
    </div>
  </article>
</div>
