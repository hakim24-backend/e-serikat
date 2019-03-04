<div class="col-md-8 col-sm-12 col-xs-12">
  <div class="row content-box">
    <div class="main_title2">
      <h2 class="title-col">Kegiatan - Kegiatan</h2>
    </div>
    <div class="body-col">

      <?php foreach ($dataProvider->allModels as $value) { ?>

        <article class="article-mini">
        <div class="inner">
        <figure>
        <a href="artikel.php">
        <img src="images/news/img09.jpg" alt="Sample Article">
        </a>
        </figure>
        <div class="padding">
        <h1><a href="artikel.php">
          <?php echo $value['title'] ; ?>
        </a></h1>
        <div class="detail">
        <div class="category">
          <a href="category.html">
            <?php
              if($value['role']==1){
                echo "Superadmin";
              }else if($value['role']==2){
                echo "Ketua Umum";
              }else if($value['role']==3){
                echo "Sekertaris Umum";
              }else if($value['role']==4){
                echo "Sekretariat";
              }else if($value['role']==5){
                echo "Bendahara";
              }else if($value['role']==6){
                echo "Ketua";
              }else if($value['role']==7){
                echo "Department";
              }else if($value['role']==8){
                echo "Seksi";
              } ?>
          </a></div>
        <div class="time"><?php echo date('F j, Y',strtotime($value['date_start']));  ?></div>
        </div>
        </div>
        </div>
        </article>


      <?php } ?>
    </div>
  </div>
</div>
