<?php
require_once '../database.php';
include 'include/head.php';
include 'include/navigation.php';
// get brands from database
$sql = "SELECT * FROM brand ORDER BY brand";
$result = $conn->query($sql);
$errors = array();

//EDIT BRAND
if(isset($_GET['edit']) && !empty($_GET['edit'])){
  $edit_id = (int)$_GET['edit'];
  $sql2 ="SELECT * FROM brand WHERE brand = '$edit_id'";
  $edit_result = $conn->query($sql2);
  $reBrand = mysqli_fetch_assoc($edit_result);
}

//DELETE BRAND
if (isset($_GET['delete']) && !empty($_GET['delete'])) {
  $delete_id = (int)$_GET['delete'];
  $sql = "DELETE FROM brand WHERE id='$delete_id' ";
  $conn->query($sql);
  header('Location:brands.php');
}

// if the submit button is SELECT

if(isset($_POST['add_submit'])){
  $brand = $_POST['brand'];
  // check if the fild is empty
  if($_POST['brand'] == "" ){
    $errors[].='You must enter a brand';
  }
  // check if the brand already exists in database
  $sql = "SELECT * FROM brand WHERE brand = '$brand' ";
  if(isset($_GET['edit'])){
    $sql = "SELECT * FROM brand WHERE brand = $brand AND id != '$edit_id'";
  }
  $resulte = $conn->query($sql);
  $count = mysqli_num_rows($resulte);
  if($count > 0){
    $errors[].= $brand. ' already exists!';
  }
  //display errors
  if(!empty($errors)){
    echo display_errors($errors);
  }else {
    //add the brand in to database
    $sql = "INSERT INTO brand (brand) VALUES ('$brand')";
    if (isset($_GET['edit'])) {
      $sql = "UPDATE brand SET brand = '$brand' WHERE id = '$edit_id'";
    }
    $conn->query($sql);
    header('Location: brands.php');
  }
}
//end of the submit
 ?>
 <div class="page-header container">
   <h2>Brands <small> Panel</small></h2>
 </div>
 <div class="container">
<table class="table table-bordered table-striped table-auto ">
  <thead style="background-color:white">
    <th><p>Add</p></th>
    <th><p>Brand</p></th>
    <th><p>Delete</p></th>
  </thead>
  <tbody>
    <?php while ($brand = mysqli_fetch_assoc($result)) : ?>
  <tr>
    <td><a href="brands.php?edit=<?=$brand['id']; ?>" class="btn btn-md btn-default"><span class="glyphicon glyphicon-pencil"></span></a></td>
    <td><?= $brand['brand'] ?></td>
    <td><a href="brands.php?delete=<?=$brand['id']; ?>" class="btn btn-md btn-default"><span class="glyphicon glyphicon-trash"></span></a></td>
  <?php endwhile; ?>
  </tr>
  </tbody>
</table>
<!-- ADD BRAND INPUT-->
<div>
  <form class="form-inline" action="brands.php<?=((isset($_GET['edit']))?'?edit='.$edit_id:''); ?>" method="post">
    <div class="form-group">
      <label for="brand"><?=((isset($_GET['edit']))?'Edit':'Add a ');?> Brand: </label>
      <input type="text" name="brand" id="brand" class="form-control" value="<?=((isset($_POST['brand']))?$_POST['brand']:''); ?>">
      <?php if (isset($_GET['edit'])): ?>
        <a href="brands.php" class="btn btn-default">Cancel</a>
      <?php endif; ?>
      <input type="submit" name="add_submit" value="<?=((isset($_GET['edit']))?'Edit':'Add');?> Brand" class="btn btn-succes">
    </div>
  </form>
</div>
</div>
 <?php
 include 'include/footer.php';
 ?>
