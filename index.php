<?php
  include_once 'includes/head.php';
  include_once 'includes/navigation.php';
  include_once 'includes/headerfull.php';
  require_once 'database.php';

$sql = "SELECT * FROM products WHERE featured = 1";
$featured = $conn->query($sql);
?>


<!--LISTA PRODUSE + FEATURED  -->
<div class="container">

  <div class="row">
    <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
      <img src="images/ss.jpeg" class="img-thumb">
      <div class="caption text-center" >
        <h3 style="color:black;"> Branded Shoes</h3>
        <div class="media-middle ">
          <h3 style="color:black;"><a href="#" class="btn btn-success" role="button" data-toggle="modal" data-target="#detail">See More</a></h3>
        </div>
      </div>
    </div>
    </div>

    <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
      <img src="images/ll.jpg" class="img-thumb">
      <div class="caption text-center" >
        <h3 style="color:black;"> Branded Shoes</h3>
        <div class="media-middle ">
          <h3 style="color:black;"><a href="#" class="btn btn-success" role="button" data-toggle="modal" data-target="#detail">See More</a></h3>
        </div>
      </div>
    </div>
    </div>

    <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
      <img src="images/dd.jpg" class="img-thumb">
      <div class="caption text-center" >
        <h3 style="color:black;"> Branded Shoes</h3>
        <div class="media-middle ">
          <h3 style="color:black;"><a href="#" class="btn btn-success" role="button" data-toggle="modal" data-target="#detail">See More</a></h3>
        </div>
      </div>
    </div>
    </div>
  </div>

  </div>

</div>
<div class="container-fluid" style="height:60px; background-color:#bbff99;">
  <div class="container">
    <h2 style="color:white;" class="text-justify"><b>FEATURED PRODUCTS<b></h2>
  </div>
</div> <br>

<!-- lista produse -->

<div class="container">
    <div class="row">
      <?php while ($product = mysqli_fetch_assoc($featured)) : ?>
      <div class="col-sm-6 col-md-4">
      <div class="thumbnail">
        <img src="<?php echo $product['image'] ?>" class="img-thumb">
        <div class="caption text-center" >
          <h3 style="color:black;"><?php echo $product['title'] ?></h3>
          <div class="media-middle ">
            <h3 style="color:black;"> $<?php echo $product['price'] ?> <a href="#" class="btn btn-success" role="button" onclick="detailsmodal(<?php echo $product['id']?>)"> Add To Card </a></h3>
          </div>
        </div>
      </div>
      </div>
    <?php  endwhile; ?>
    </div>


    <?php
    include_once 'includes/footer.php';
    ?>

        <!-- detailes modal -->


    </div>

    <!-- end of container -->
