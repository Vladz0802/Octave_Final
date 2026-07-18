<?php if($edit_product){ ?>

<div class="modal fade" id="editProductModal" tabindex="-1">

    <div class="modal-dialog modal-lg">

        <form action="" method="POST" enctype="multipart/form-data">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="fw-bold">

                        Edit Product

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">

                    </button>

                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        name="product_id"
                        value="<?php echo $edit_product['product_id']; ?>">

                    <input
                        type="hidden"
                        name="old_image"
                        value="<?php echo $edit_product['image']; ?>">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Product Name

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="product_name"
                                value="<?php echo $edit_product['product_name']; ?>"
                                required>

                        </div>

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Category

                            </label>

                            <select
                                class="form-select"
                                name="category_id"
                                required>

                                <?php

                                $category = mysqli_query($conn,"SELECT * FROM categories");

                                while($cat = mysqli_fetch_assoc($category)){

                                ?>

                                <option
                                    value="<?php echo $cat['category_id']; ?>"

                                    <?php

                                    if($cat['category_id']==$edit_product['category_id']){

                                        echo "selected";

                                    }

                                    ?>

                                >

                                    <?php echo $cat['category_name']; ?>

                                </option>

                                <?php

                                }

                                ?>

                            </select>

                        </div>

                        <div class="col-md-12 mb-3">

                            <label class="form-label">

                                Description

                            </label>

                            <textarea
                                class="form-control"
                                rows="3"
                                name="description"
                                required><?php echo $edit_product['description']; ?></textarea>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Price

                            </label>

                            <input
                                type="number"
                                class="form-control"
                                name="price"
                                value="<?php echo $edit_product['price']; ?>"
                                required>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Stock

                            </label>

                            <input
                                type="number"
                                class="form-control"
                                name="stock"
                                value="<?php echo $edit_product['stock']; ?>"
                                required>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Product Image

                            </label>

                            <input
                                type="file"
                                class="form-control"
                                name="image">

                        </div>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        name="update_product">

                        <i class="bi bi-pencil-square"></i>

                        Update Product

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>


<?php } ?>