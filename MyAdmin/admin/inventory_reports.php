<?php

include("../includes/session.php");
include("../includes/db_connection.php");
include("../includes/header.php");

?>

<div class="container-fluid">

    <div class="row">

        <?php include("../includes/sidebar.php"); ?>

        <div class="col-lg-10 p-4">

            <div class="topbar d-flex justify-content-between align-items-center">

                <div>

                    <h2 class="fw-bold">
                        Inventory Summary
                    </h2>

                    <small>
                        View all products currently available in the inventory.
                    </small>

                </div>

                <button
                    class="btn btn-success"
                    onclick="window.print()">

                    <i class="bi bi-printer"></i>

                    Print Report

                </button>

            </div>

            <h4 class="fw-bold mt-4 mb-3">

                Product Inventory

            </h4>

            <div class="card shadow">

                <div class="card-body">

                    <table class="table table-hover align-middle">

                        <thead class="table-light">

                            <tr>

                                <th>Image</th>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Status</th>

                            </tr>

                        </thead>

                        <tbody>

                        <?php

                        $sql = "SELECT products.*, categories.category_name
                                FROM products
                                LEFT JOIN categories
                                ON products.category_id = categories.category_id";

                        $result = mysqli_query($conn,$sql);

                        if(mysqli_num_rows($result) > 0){

                            while($row = mysqli_fetch_assoc($result)){

                        ?>

                            <tr>

                                <td>

                                    <img
                                        src="../../assets/images/<?php echo $row['image']; ?>"
                                        width="70"
                                        height="70"
                                        style="object-fit:cover;border-radius:10px;">

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

                                    elseif($row['stock'] <= 5){

                                        echo "<span class='badge bg-warning text-dark'>Low Stock</span>";

                                    }

                                    else{

                                        echo "<span class='badge bg-success'>In Stock</span>";

                                    }

                                    ?>

                                </td>

                            </tr>

                        <?php

                            }

                        }

                        else{

                        ?>

                            <tr>

                                <td colspan="6" class="text-center text-muted py-5">

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

                </div>

            </div>

            <?php

            $total_products = mysqli_fetch_assoc(
                mysqli_query($conn,
                "SELECT COUNT(*) AS total FROM products")
            );

            $low_stock = mysqli_fetch_assoc(
                mysqli_query($conn,
                "SELECT COUNT(*) AS total FROM products WHERE stock <= 5")
            );

            $total_value = mysqli_fetch_assoc(
                mysqli_query($conn,
                "SELECT SUM(price * stock) AS total FROM products")
            );

            ?>

            <div class="row mt-4">

                <div class="col-md-4">

                    <div class="card shadow">

                        <div class="card-body text-center">

                            <i class="bi bi-box-seam fs-1 text-primary"></i>

                            <h5 class="mt-3">

                                Total Products

                            </h5>

                            <h2>

                                <?php echo $total_products['total']; ?>

                            </h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card shadow">

                        <div class="card-body text-center">

                            <i class="bi bi-exclamation-triangle fs-1 text-warning"></i>

                            <h5 class="mt-3">

                                Low Stock

                            </h5>

                            <h2>

                                <?php echo $low_stock['total']; ?>

                            </h2>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

                    <div class="card shadow">

                        <div class="card-body text-center">

                            <i class="bi bi-cash-stack fs-1 text-success"></i>

                            <h5 class="mt-3">

                                Total Inventory Value

                            </h5>

                            <h2>

                                ₱ <?php echo number_format($total_value['total'],2); ?>

                            </h2>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js"></script>

<?php include("../includes/footer.php"); ?>