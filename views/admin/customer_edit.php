<?php require "views/partials/admin_header.php"; ?>
<!-- <link href="/public/css/user_profile_style.css" rel="stylesheet"> -->

<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <div class="row g-3 mb-4 align-items-center justify-content-between">
                <div class="main-container">
                    <!-- Profile Card -->
                    <div class="card shadow-lg border-0">
                        <div class="m-4 d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <img src="<?= !empty($customer['image_url']) ? htmlspecialchars($customer['image_url']) :'/public/images/admin-user-profile.png'; ?>"
                                    alt="Profile Image" class="rounded-circle" style="width: 100px; height: 100px;">
                                <h3 class="text-success ms-3">
                                    <?php
                                    $formattedFirstName = str_replace('-', ' ', strtolower($customer['first_name']));
                                    $formattedFirstName = ucwords($formattedFirstName);
                                    $formattedLastName = str_replace('-', ' ', strtolower($customer['last_name']));
                                    $formattedLastName = ucwords($formattedLastName);
                                    echo htmlspecialchars($formattedFirstName . ' ' . $formattedLastName);
                                    ?>
                                </h3>

                            </div>
                            <button class="btn btn-success text-white" data-bs-toggle="modal"
                                data-bs-target="#editProfileModal">
                                Edit Customer
                            </button>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label>Customer ID</label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($customer['id']); ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Email</label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($customer['email']); ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>First Name</label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($customer['first_name']); ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Last Name</label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($customer['last_name']); ?>" readonly>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label>Phone Number</label>
                                    <input type="text" class="form-control"
                                        value="<?= htmlspecialchars($customer['phone_number']); ?>" readonly>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <label>Address</label>
                                    <textarea class="form-control" rows="2"
                                        readonly><?= htmlspecialchars($customer['address']); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <!-- Edit Customer Modal -->
                    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="/admin/customer_update/<?= htmlspecialchars($customer['id']); ?>"
                                    method="POST" enctype="multipart/form-data">
                                    <div class="modal-header bg-success text-white">
                                        <h5 class="modal-title" id="editProfileModalLabel">Edit Customer</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label>Customer ID</label>
                                                <input type="text" class="form-control" name="id"
                                                    value="<?= htmlspecialchars($customer['id']); ?>" readonly>
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Email</label>
                                                <input type="text" class="form-control" name="email"
                                                    value="<?= htmlspecialchars($customer['email']); ?>">
                                            </div>

                                            <div class="col-md-6 mb-3">
                                                <label>First Name</label>
                                                <input type="text" class="form-control" name="first_name"
                                                    value="<?= htmlspecialchars($customer['first_name']); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Last Name</label>
                                                <input type="text" class="form-control" name="last_name"
                                                    value="<?= htmlspecialchars($customer['last_name']); ?>">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label>Phone Number</label>
                                                <input type="text" class="form-control" name="phone_number"
                                                    value="<?= htmlspecialchars($customer['phone_number']); ?>">
                                            </div>
                                            <div class="col-md-12 mt-3">
                                                <label>Upload Image</label>
                                                <input type="file" class="form-control" name="image_url">
                                            </div>

                                            <div class="col-md-12 mt-3 mb-3">
                                                <label>Address</label>
                                                <textarea class="form-control" name="address"
                                                    rows="5"><?= htmlspecialchars($customer['address']); ?></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-success">Save Changes</button>
                                        <button type="button" class="btn btn-danger"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- SweetAlert Script -->
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
                    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
                    <script>
                        function updateUserProfile() {
                            Swal.fire({
                                title: 'Success!',
                                text: 'Your profile has been updated.',
                                icon: 'success',
                                confirmButtonColor: '#3B5D50'
                            });
                        }

                        function removeItem(itemName) {
                            Swal.fire({
                                title: 'Are you sure?',
                                text: "You want to remove " + itemName + " from your wishlist!",
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#d33',
                                cancelButtonColor: '#3085d6',
                                confirmButtonText: 'Yes, remove it!'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    Swal.fire({
                                        title: 'Removed!',
                                        text: itemName + ' has been removed from your wishlist.',
                                        icon: 'success',
                                        confirmButtonColor: '#3B5D50'
                                    });
                                }
                            });
                        }
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>