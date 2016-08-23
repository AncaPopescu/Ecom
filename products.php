<?php
require_once $_SERVER['DOCUMENT_ROOT'].'../ecomerce/database.php';
include 'include/head.php';
include 'include/navigation.php';
$thePath = '';

// new set
if(isset($_GET['add'])  || isset($_GET['edit'])){
  $brandquery = $conn->query("SELECT * FROM brand ORDER BY brand");
  $parentquery = $conn->query("SELECT * FROM categories WHERE parent = 0 ORDER BY category");
  /// turnary operator
  $title = ((isset($_POST['title']) && $_POST['title'] != '')?$_POST['title'] : '');
  $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?$_POST['brand'] : '');
  $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?$_POST['parent'] : '');
  $categ = ((isset($_POST['child']) && $_POST['child'] != '')?$_POST['child'] : '');
  $price = ((isset($_POST['price']) && $_POST['price'] != '')?$_POST['price'] : '');
  $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?$_POST['list_price'] : '');
  $description = ((isset($_POST['description']) && $_POST['description'] != '')?$_POST['description'] : '');
  $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?$_POST['sizes'] : '');
  $sizes = rtrim($sizes, ',');
  $saved_imag = '';

  if(isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit']; // takes our values from
    $productersult = $conn->query("SELECT * FROM products WHERE id= '$edit_id'");
    $product = mysqli_fetch_assoc($productersult);
    //delete or edite the image
    if(isset($_GET['delete_image'])){
      $image_url = $_SERVER['DOCUMENT_ROOT'].$product['image'];
      unset($image_url); /// delets image
      $conn->query("UPDATE products SET image = '' WHERE id = '$edit_id'"); // reset`s the database
      header('Location: products.php?edit='.$edit_id);

    }

    $categ = ((isset($_POST['child']) && $_POST['child'] != '')?$_POST['child'] : $product['categories']);
    $title = ((isset($_POST['title']) && $_POST['title'] != '')?$_POST['title'] : $product['title']);
    $brand = ((isset($_POST['brand']) && $_POST['brand'] != '')?$_POST['brand'] : $product['brand']);
    $parent_rez = $conn->query("SELECT * FROM categories WHERE id = '$categ'");
    $parent_afis = mysqli_fetch_assoc($parent_rez);
    $parent = ((isset($_POST['parent']) && $_POST['parent'] != '')?$_POST['parent'] : $parent_afis['parent']);
    $price = ((isset($_POST['price']) && $_POST['price'] != '')?$_POST['price'] : $product['price']);
    $list_price = ((isset($_POST['list_price']) && $_POST['list_price'] != '')?$_POST['list_price'] : $product['list_price']);
    $description = ((isset($_POST['description']) && $_POST['description'] != '')?$_POST['description'] : $product['description']);
    $sizes = ((isset($_POST['sizes']) && $_POST['sizes'] != '')?$_POST['sizes'] : $product['sizes']);
    $sizes = rtrim($sizes, ',');
    $saved_imag = (($product['image'] != '')?$product['image']: '');
  }
  /// input the sizes
  if($_POST) {
    $parentID = "";

    $brand = $_POST['brand'];
    $categories = $_POST['child'];
    $price = $_POST['price'];
    $list_price = $_POST['list_price'];
    $sizes = $_POST['sizes'];
    $description = $_POST['description'];
    $thePath = "";
  if (!empty($_POST['sizes'])) {
    $sizeString = $_POST['sizes'];
    $sizeString = rtrim($sizeString,',');
    $sizesArray = explode(',',$sizeString);
    $sreArray = array();
    $qreArray = array();
    foreach ($sizesArray as $ret) {
      $s = explode(':', $ret);
      $sreArray[] = $s[0];
      $qreArray[] = $s[1];
    }

  }else{ $sizesArray = array();}
  $obligatoriu = array('title', 'brand', 'price', 'parent', 'child', 'description');
  foreach ($obligatoriu as $fild) {
    if ($_POST[$fild] == '') {
      $errors[] =  'All filds with an asterisk are required! ';
      break;
    }
  }
  // check the image
  if(!empty($_FILES)) {
    $photo = $_FILES['photo'];
    $name = $photo['name'];
    $nameArray = explode('.' , $name);
    $fileName =$nameArray[0];
    $fileExtension = $nameArray[1];
    $mime = explode('/', $photo['type']);
    $mimeType =$mime[0];
    $mimeExtansion = $mime[1];
    $temp_location = $photo['tmp_name'];
    $size = $photo['size'];
    $allowed = array('png', 'jpg', 'jpeg', 'gif');

    //upload location
    $thePath = '/ecomerce/images/'.$name;
    // if the mime type is an ImagickPixel
    if($mimeType != 'image') {
      $errors[] = 'The file must be an image ! ';
    }
    if (!in_array($fileExtension, $allowed)) {
      $errors[] = 'The photo must be a .png, .jpg, .jpeg, .gif file !';
    }
  }

  //afiseaza erorile

  if(!empty($errors)){
    echo display_errors($errors);
  }else {
    if(isset($_GET['edit'])){
      $insertSql =  "UPDATE `products` SET title = '$title', price = '$price', list_price = '$list_price', brand = '$brand', categories = '$categ', sizes = '$sizes', description = '$description', image = '$thePath' WHERE `products`.`id` = $edit_id";
    }else{
    move_uploaded_file($temp_location, $upLoc);
    $insertSql = "INSERT INTO products (`title`, `price`, `list_price`, `brand`, `categories`, `description`, `sizes`, `image`)
    VALUES ('$title', '$price', '$list_price', '$brand', '$categ', '$description', '$sizes', '$thePath')";
  }
    $conn->query($insertSql);
    header('Location: products.php');

  }
  // upload file an insert in to database

}
  ///
?>
<div class="container">
<h2 class="text-center"><?=((isset($_GET['edit']))?'Edit': 'Add a new'); ?> product: </h2><hr>
<form action="products.php?<?=((isset($_GET['edit']))?'edit='.$edit_id:
  'add=1' );?>" method="post" enctype="multipart/form-data">
  <div class="form-group col-md-3">
    <label for="title">Title*:</label>
    <input type="text" name="title" id="title"  class="form-control" value="<?=$title; ?>">
  </div>
  <div class="form-group col-md-3">
    <label for="brand">Brand*:</label>
    <select class="form-control" name="brand" id="brand">
      <option value=""<?=(($brand == "")? 'selected' : ''); ?>> </option>
      <?php while ($b = mysqli_fetch_assoc($brandquery)) : ?>
        <option value="<?=$b['id']; ?>"<?=(($brand == $b['id'])? 'selected' : ''); ?>><?=$b['brand']; ?> </option>
    <?php endwhile; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label for="parent">Parent category* :</label>
    <select class="form-control" id="parent" name="parent">
      <option value="<?=(($parent == "")? 'selected' : ''); ?>"> </option>
      <?php while ($p = mysqli_fetch_assoc($parentquery)) : ?>
        <option value="<?=$p['id']; ?>"<?=(($parent == $p['id'])? 'selected' : ''); ?>><?=$p['category']; ?></option>
     <?php  endwhile; ?>
    </select>
  </div>
  <div class="form-group col-md-3">
    <label for="child">Child category *: </label>
    <select class="form-control" name="child" id="child">
    </select>
  </div>
  <div class="form-group col-md-3">
    <label for="price">Price*:</label>
    <input type="text" name="price"  class="form-control" id="price" value="<?=$price;?>">
  </div>
  <div class="form-group col-md-3">
    <label for="list_price">List price*:</label>
    <input type="text" name="list_price"  class="form-control" id="list_price" value="<?=$list_price; ?>">
  </div>
  <div class="form-group col-md-3">
    <label>Quanteties and Sizes</label>
    <button class="btn btn-default form-control" onclick="jQuery('#sizesModal').modal('toggle'); return false ;">Quanteties and Sizes</button>
  </div>
  <div class="form-group col-md-3">
    <label for="sizes">Sizes and Quantity Prev</label>
    <input type="text" class="form-control" name="sizes" value="<?=$sizes; ?>" id="sizes" readonly="">
  </div>
  <div class="form-group col-md-6">
    <?php if($saved_imag != ''): ?>
      <div class="savedimage">
        <img src="<?=$saved_imag; ?>" alt="saved image" /><br>
        <p class="btn btn-default"><a href="products.php?delete_image=1&edit=<?=$edit_id; ?>" class="text-danger">Delete Image</a></p>
      </div>
    <?php else: ?>
      <label for="photo">Product Photo:</label>
      <input type="file" name="photo" id="photo" class="form-control">
    <?php endif; ?>
  </div>
  <div class="form-group col-md-6">
    <label for="description">Description:</label>
    <textarea name="description" id="description" class="form-control" rows="6"><?=$description; ?></textarea>
  </div>
  <div class="form-group pull-right">
    <?php if (isset($_GET['edit'])) : ?>
    <a href="products.php" class="btn btn-default">Cancel</a>
  <?php endif; ?>
    <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add')?> Product" class="btn btn-success">

  </div>
</form>

<!--  MODAL  -->
<?php
include_once 'add_new_product.php';
?>
<!--  MODAL  -->

</div>
<?php }else{
//
$sql = "SELECT * FROM products WHERE deleted = 0";
$p_results = $conn->query($sql);
// featured

if (isset($_GET['featured'])){
  $id = (int)$_GET['id'];
  $featured = (int)$_GET['featured'];
  $sql_featured = "UPDATE products SET featured = '$featured' WHERE id = '$id'";
  $conn->query($sql_featured);
  header('Location: products.php');
}

 ?>
<div class="container">
  <div class="page-header container">
    <h2>Products <small> Panel</small></h2>
    <a href="products.php?add=1" class="btn btn-default pull-right" id="add-product-btn">Add product</a>
  </div>
<table class="table table-bordered table-striped table-auto ">
  <thead style="background-color:white">
    <th><p></p></th>
    <th><p>Product</p></th>
    <th><p>Price</p></th>
    <th><p>Category</p></th>
    <th><p>Featured</p></th>
    <th><p>Sold</p></th>
  </thead>
  <tbody>
    <?php while ($product = mysqli_fetch_assoc($p_results)) :
        $childID = $product['categories'];
        $categ_sql = "SELECT * FROM categories WHERE id = '$childID'";
        $result = $conn->query($categ_sql);
        $child = mysqli_fetch_assoc($result);
        $parentID = $child['parent'];
        $parent_sql = "SELECT * FROM categories WHERE id = '$parentID'";
        $parent_result = $conn->query($parent_sql);
        $parent = mysqli_fetch_assoc($parent_result);
        $category = $parent['category'].'-'.$child['category'];
    ?>
      <tr>
        <td><p>
          <a href="products.php?edit=<?=$product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
          <a href="products.php?delete=<?=$product['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-trash"></span></a></p>
        </td>
        <td><p><?=$product['title']; ?></p></td>
        <td><p><?=money($product['price']); ?></p></td>
        <td><p><?=$category ?></p></td>
        <td><p>
          <a href="products.php?featured=<?=(($product['featured'] == 0)? '1' : '0'); ?>&id=<?=$product['id']; ?>" class="btn btn-xs btn-default">
          <span class="lyphicon glyphicon-<?=(($product['featured'] == 1)? 'minus' : 'plus'); ?> "></span></a>
            &nbsp <?=(($product['featured'] == 1)? 'Featured Product' : ''); ?>
          </p></td>
        <td><p>0</p></td>
      </tr>
    <?php endwhile; ?>
  </tbody>
</table>
</div>





<?php }
include 'include/footer.php';
 ?>

<script>
  jQuery('document').ready(function(){
    get_child_option('<?=$categ; ?>');
  });
</script>
