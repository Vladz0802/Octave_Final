<div class="modal fade" id="addProductModal">

    <div class="modal-dialog modal-lg">

        <form action="" method="POST" enctype="multipart/form-data">

            <div class="modal-content">

                <div class="modal-header">

                    <h5 class="fw-bold">

                        Add New Product

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">

                    </button>

                </div>

                <div class="modal-body">

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="form-label">

                                Product Name

                            </label>

                            <input
                                type="text"
                                class="form-control"
                                name="product_name"
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

                                <option value="">

                                    Select Category

                                </option>

                                <?php

                                $category = mysqli_query($conn,"SELECT * FROM categories");

                                while($row = mysqli_fetch_assoc($category)){

                                ?>

                                    <option value="<?php echo $row['category_id']; ?>">

                                        <?php echo $row['category_name']; ?>

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
                                required></textarea>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Price

                            </label>

                            <input
                                type="number"
                                class="form-control"
                                name="price"
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
                                required>

                        </div>

                        <div class="col-md-4 mb-3">

                            <label class="form-label">

                                Product Image

                            </label>

                            <input
                                type="file"
                                class="form-control"
                                name="image"
                                accept="image/*"
                                required>

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
                        name="save_product">

                        <i class="bi bi-floppy"></i>

                        Save Product

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>
