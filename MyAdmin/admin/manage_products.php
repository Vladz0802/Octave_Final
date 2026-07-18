<?php
include("../includes/functions.php");
include("../includes/session.php");
include("../includes/db_connection.php");
include("../includes/header.php");

/** @var mysqli $conn */

?>

<?php

if (isset($_GET['delete_id'])) {

    $delete_id = $_GET['delete_id'];

    $get_image = mysqli_query($conn, "
        SELECT image
        FROM products
        WHERE product_id='$delete_id'
    ");

    $row = mysqli_fetch_assoc($get_image);

    if ($row) {

        if (file_exists("../../assets/images/" . $row['image'])) {
            unlink("../../assets/images/" . $row['image']);
        }

    }

    $sql = "DELETE FROM products
            WHERE product_id='$delete_id'";

    $delete = mysqli_query($conn, $sql);

    if ($delete) {

        addAuditLog($conn, $_SESSION['admin'], "Deleted Product");

        echo "<script>
                alert('Product Deleted Successfully.');
                window.location='manage_products.php';
              </script>";

    } else {

        echo "<script>
                alert('Unable to delete product.');
              </script>";

    }

}
?>

<?php

$edit_product = null;

if(isset($_GET['edit_id'])){

    $edit_id = $_GET['edit_id'];

    $edit_sql = "SELECT * FROM products WHERE product_id='$edit_id'";

    $edit_result = mysqli_query($conn, $edit_sql);

    if(mysqli_num_rows($edit_result) == 1){

        $edit_product = mysqli_fetch_assoc($edit_result);

    }

}

if (isset($_POST['update_product'])) {

    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $old_image = $_POST['old_image'];

    if ($_FILES['image']['name'] != "") {

        $image = $_FILES['image']['name'];
        $temp_image = $_FILES['image']['tmp_name'];

        move_uploaded_file($temp_image, "../../assets/images/" . $image);

    } else {

        $image = $old_image;

    }

    $sql = "UPDATE products
            SET category_id='$category_id',
                product_name='$product_name',
                description='$description',
                price='$price',
                stock='$stock',
                image='$image'
            WHERE product_id='$product_id'";

    $update = mysqli_query($conn, $sql);

    if ($update) {

        addAuditLog($conn, $_SESSION['admin'], "Updated Product");

        echo "<script>
                alert('Product Updated Successfully.');
                window.location='manage_products.php';
              </script>";

    } else {

        echo "<script>
                alert('Unable to update product.');
              </script>";

    }

}

?>
<?php

if (isset($_POST['delete_selected'])) {

    if (isset($_POST['delete_ids'])) {

        foreach ($_POST['delete_ids'] as $delete_id) {

            $get_image = mysqli_query($conn, "
                SELECT image
                FROM products
                WHERE product_id='$delete_id'
            ");

            $image_row = mysqli_fetch_assoc($get_image);

            if ($image_row) {

                if (file_exists("../../assets/images/" . $image_row['image'])) {
                    unlink("../../assets/images/" . $image_row['image']);
                }

            }

            mysqli_query($conn, "
                DELETE FROM products
                WHERE product_id='$delete_id'
            ");

        }

        addAuditLog($conn, $_SESSION['admin'], "Deleted Multiple Products");

        echo "<script>
                alert('Selected products deleted successfully.');
                window.location='manage_products.php';
              </script>";

    } else {

        echo "<script>
                alert('Please select at least one product.');
              </script>";

    }

}

?>

<?php

if (isset($_POST['save_product'])) {

    $product_name = $_POST['product_name'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    $image = $_FILES['image']['name'];
    $temp_image = $_FILES['image']['tmp_name'];

    move_uploaded_file($temp_image, "../../assets/images/" . $image);

    $sql = "INSERT INTO products
            (category_id, product_name, description, price, stock, image)
            VALUES
            ('$category_id',
             '$product_name',
             '$description',
             '$price',
             '$stock',
             '$image')";

    $insert = mysqli_query($conn, $sql);

    if ($insert) {

        addAuditLog($conn, $_SESSION['admin'], "Added Product");

        echo "<script>
                alert('Product Added Successfully.');
                window.location='manage_products.php';
              </script>";

    } else {

        echo "<script>
                alert('Unable to save product.');
              </script>";

    }

}

?>

<div class="container-fluid">

    <div class="row">

        <?php include("../includes/sidebar.php"); ?>

        <div class="col-lg-10 p-4">

            <div class="topbar d-flex justify-content-between align-items-center">

                <div>

                    <h2 class="fw-bold">
                        Manage Products
                    </h2>

                    <small>
                        Add, edit and manage all products.
                    </small>

                </div>

                <button
                    class="btn btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#addProductModal">

                    <i class="bi bi-plus-circle"></i>

                    Add Product

                </button>

            </div>

            <div class="card shadow mt-4">

                <div class="card-body">

                    <div class="row mb-3">

                        <div class="col-md-4">

                        <form action="" method="GET">

        <div class="input-group">

        <input
            type="text"
            class="form-control"
            name="search"
            placeholder="Search Product..."
            value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

        <button
            class="btn btn-primary"
            type="submit">

            <i class="bi bi-search"></i>

            Search

        </button>

    </div>

</form>

                        </div>

                    </div>

                    <form action="" method="POST">
                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                           <thead class="table-light">

                        <tr>
                       
                        <th>Image</th>

                        <th>Product</th>

                        <th>Category</th>

                        <th>Price</th>

                        <th>Stock</th>

                        <th>Status</th>

                        <th width="180">Actions</th>

</tr>

</thead>

                        </thead>

                        <tbody>

<?php

if(isset($_GET['search'])){

    $search = mysqli_real_escape_string($conn,$_GET['search']);

    $sql = "SELECT products.*, categories.category_name

            FROM products

            LEFT JOIN categories

            ON products.category_id = categories.category_id

            WHERE

            product_name LIKE '%$search%'

            OR

            category_name LIKE '%$search%'";

}
else{

    $sql = "SELECT products.*, categories.category_name

            FROM products

            LEFT JOIN categories

            ON products.category_id = categories.category_id";

}

$result = mysqli_query($conn,$sql);

if(mysqli_num_rows($result) > 0){

    while($row = mysqli_fetch_assoc($result)){

?>

<tr>

    <td>

        <input
            type="checkbox"
            class="productCheckbox"
            name="delete_ids[]"
            value="<?php echo $row['product_id']; ?>">

    </td>

    <td>

        <img
            src="../../assets/images/<?php echo $row['image']; ?>"
            width="70"
            height="70"
            style="object-fit:cover; border-radius:10px;">

    </td>

    <td>

        <?php echo $row['product_name']; ?>

    </td>

    <td>

        <?php echo $row['category_name']; ?>

    </td>

    <td>

        ₱ <?php echo number_format($row['price'],2); ?>

    </td>

    <td>

        <?php echo $row['stock']; ?>

    </td>

    <td>

        <?php

        if($row['stock'] <= 0){

            echo "<span class='badge bg-danger'>Out of Stock</span>";

        }

        else if($row['stock'] <= 5){

            echo "<span class='badge bg-warning text-dark'>Low Stock</span>";

        }

        else{

            echo "<span class='badge bg-success'>In Stock</span>";

        }

        ?>

    </td>

   <td>

    <a
        href="manage_products.php?edit_id=<?php echo $row['product_id']; ?>"
        class="btn btn-sm btn-primary">

        <i class="bi bi-pencil-square"></i>

    </a>
<a
    href="manage_products.php?delete_id=<?php echo $row['product_id']; ?>"
    class="btn btn-sm btn-danger"

    onclick="return confirm('Are you sure you want to delete this product?');">

    <i class="bi bi-trash"></i>

</a>

</td>

</tr>

<?php

    }

}

else{

?>

<tr>

    <td colspan="7" class="text-center text-muted py-5">

        <i class="bi bi-box-seam fs-1"></i>

        <br><br>

        No products available.

    </td>

</tr>

<?php

}

?>

</tbody>

                    </table>
                    <div class="mt-3">

                    <button
                    type="submit"
                    class="btn btn-danger"
                    name="delete_selected"

                    onclick="return confirm('Delete all selected products?');">

                   <i class="bi bi-trash"></i>

                    Delete Selected

                  </button>

</div>

</form>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include("../includes/add_product_modal.php"); ?>

<?php include("../includes/edit_product_modal.php"); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<?php if($edit_product){ ?>

<script>

document.getElementById("selectAll").addEventListener("change", function(){

    let checkboxes = document.querySelectorAll(".productCheckbox");

    checkboxes.forEach(function(box){

        box.checked = document.getElementById("selectAll").checked;

    });

});

</script>

<script>

document.addEventListener("DOMContentLoaded", function(){

    var editModal = new bootstrap.Modal(document.getElementById("editProductModal"));

    editModal.show();

});

</script>

<?php } ?>

<?php include("../includes/footer.php"); ?>