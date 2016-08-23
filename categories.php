<?php

require_once $_SERVER['DOCUMENT_ROOT'].'../ecomerce/database.php';
include 'include/head.php';
include 'include/navigation.php';
$sql ="SELECT * FROM categories WHERE parent = 0";
$result = $conn->query($sql);
$errors = array();
$category = "";
$post_parent = "";

// delete elements

if(isset($_GET['delete']) && !empty($_GET['delete'])){
  $delete_id = (int)$_GET['delete'];
  $sql = "DELETE FROM `categories` WHERE `categories`.`id` = '$delete_id' ";
  $result =  $conn->query($sql);
  $category = mysqli_fetch_assoc($result);
  if($category['parent'] == 0) {
    $sql = "DELETE * FROM categories WHERE parent = '$delete_id'";
    $conn->query($sql);
  }
  $deleteSQL = "DELETE FROM `categories` WHERE `categories`.`id` = '$delete_id' ";
  $conn->query($deleteSQL);
  header('Location: categories.php');
}

// edit the categories

if(isset($_GET['edit']) && !empty($_GET['edit'])){
  $edit_id = (int)$_GET['edit'];
  $updateSQL = "SELECT * FROM `categories` WHERE `categories`.`id` = $edit_id";
  $edit_result = $conn->query($updateSQL);
  $edit_categ = mysqli_fetch_assoc($edit_result);
}

// Process the input in the form
if(isset($_POST) && !empty($_POST)) {
  $post_parent = $_POST['parent'];
  $category = $_POST['category'];
  $sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent'";
  if(isset($_GET['edit'])){
    $id = $edit_categ['id'];
    $sqlform = "SELECT * FROM categories WHERE category = '$category' AND parent = '$post_parent' AND id != '$id'";
  }
  $fresult = $conn->query($sqlform);
  $count = mysqli_num_rows($fresult);
  // category is blank
  if ($category == '') {
    $errors [].='The category can not be blank';
  }
  // if exists in database
  if ($count > 0) {
    $errors[] .= $category. ' already exists. Pleas enter a new category!';
  }
  // Display errors or UpDate database
  if (!empty($errors)) {
    //display the errors
      $eroare =  display_errors($errors); ?>
      <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
      <script>
        jQuery('document').ready(function(){
          $('#err').html('<?=$eroare; ?>');
        });
      </script>
  <?php } else {
    // update the database
    $updatesql = "INSERT INTO `categories` (`id`, `category`, `parent`) VALUES (NULL, '$category', '$post_parent')";
    if(isset($_GET['edit'])){
      $updatesql = "UPDATE categories SET category = '$category' , parent = '$post_parent' WHERE id= '$edit_id'";
    }
    $conn->query($updatesql);
    header('Location: categories.php');

  }

}
 $category_value = " ";
 $parent_value = 0;
 if(isset($_GET['edit'])){
   $category_value = $edit_categ['category'];
   $parent_value = $edit_categ['parent'];

 }else {
   if (isset($_POST)) {
     $category_value = $category;
     $parent_value = $post_parent;

   }
 }
?>
<div class="container">
<h2 class="text-center">Categorie</h2><hr>
<div class="row">

  <!-- FORM-->
  <div class="col-md-6">
    <form class="form" action="categories.php<?=((isset($_GET['edit']))?'?edit='.$edit_id: ''); ?>" method="post">
      <legend><?=((isset($_GET['edit']))?'Edit':'Add a' ); ?> category: </legend>
      <div id="err"></div>
      <div class="form-group">
        <label for="parent">Parent</label>
        <select class="form-control" name="parent" id="parent">
          <option value="0" <?=(($parent_value == 0)? 'selected': ''); ?>>Parent</option>
          <?php while ($parent = mysqli_fetch_assoc($result)) : ?>
            <option value="<?=$parent['id']; ?>"<?=(($parent_value == $parent['id'])?'selected' : ''); ?>><?=$parent['category']; ?></option>
          <?php endwhile;
          ?>
        </select>
      </div>
      <div class="form-group">
        <label for="category">Category</label>
        <input type="text" class="form-control" id="category" name="category" value="<?=$category_value; ?>">
      </div>
      <div class="form-group">
        <input type="submit" value="<?=((isset($_GET['edit']))?'Edit':'Add ' ); ?> Category" class="btn btn-success">
      </div>
    </form>
  </div>

  <!-- CATEGORIES-->
  <div class="col-md-6">
    <table class="table table-bordered table-auto">
      <thead style="background-color:white">
        <th>
          <p>Category</p>
        </th>
        <th>
          <p>Parent</p>
        </th>
        <th>
          <p></p>
        </th>
      </thead>
      <tbody>
        <?php
        $sql ="SELECT * FROM categories WHERE parent = 0";
        $result = $conn->query($sql);
         while ($parent = mysqli_fetch_assoc($result)) :
          $parent_id = $parent['id'];
            $sql2 = "SELECT * FROM categories WHERE parent = '$parent_id'";
            $cresult = $conn->query($sql2);
            ?>
        <tr class="bg-primary">
          <td><?=$parent['category'];?></td>
          <td>Parent</td>
          <td>
            <a href="categories.php?edit=<?=$parent['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
            <a href="categories.php?delete=<?=$parent['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-trash"></span></a>
          </td>
        </tr>
          <?php while ($child = mysqli_fetch_assoc($cresult)): ?>
            <tr class="bg-info">
              <td><?=$child['category'];?></td>
              <td><?=$parent['category'] ?></td>
              <td>
                <a href="categories.php?edit=<?=$child['id']; ?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-pencil"></span></a>
                <a href="categories.php?delete=<?=$child['id'];?>" class="btn btn-xs btn-default"><span class="glyphicon glyphicon-trash"></span></a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
</div>
<?php
include 'include/footer.php';
?>
