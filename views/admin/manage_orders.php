<?php
require "views/partials/admin_header.php";
$search_query = isset($_GET['search']) ? $_GET['search'] : '';
$filtered_orders = array_filter($orders, function ($order) use ($search_query) {
    return stripos($order['status'], $search_query) !== false;
});
// Default to all orders if no search query is provided
if ($search_query === '') {
    $filtered_orders = $orders;
}
$items_per_page = 20;
$current_page = max(1, isset($_GET['page']) ? (int) $_GET['page'] : 1);
$start_index = ($current_page - 1) * $items_per_page;
$paginated_orders = array_slice($filtered_orders, $start_index, $items_per_page);
$total_items = count($filtered_orders);
$total_pages = ceil($total_items / $items_per_page);
?>

<div class="app-wrapper">
    <div class="app-content pt-3 p-md-3 p-lg-4">
        <div class="container-xl">
            <div class="row g-3 mb-4 align-items-center justify-content-between shadow-sm p-3 bg-light rounded">
                <div class="col-auto">
                    <h1 class="app-page-title mb-0 text-success fw-bold" style="font-size: 2rem; text-shadow: 1px 1px 2px #d4edda;">
                        <i class="fas fa-users me-3"></i>Orders
                    </h1>
                </div>
                <div class="col-auto">
                    <div class="page-utilities">
                        <form class="docs-search-form row gx-1 align-items-center" method="GET" action="">
                            <div class="col-auto">
                                <input type="text" id="search-docs" name="search"
                                       value="<?= htmlspecialchars($search_query) ?>"
                                       class="form-control bg-light border-success rounded-pill"
                                       placeholder="Search orders....">
                            </div>
                            <div class="col-auto">
                                <button type="submit" class="btn rounded-pill"
                                        style="background-color: #5bb377; border-color: #5bb377;">
                                    <i class="fas fa-search text-white"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-borderless shadow-sm rounded">
                    <thead class="table-success">
                        <tr class="text-center">
                            <th class="text-nowrap">ID</th>
                            <th class="text-nowrap">Customer ID</th>
                            <th class="text-nowrap">Order Date</th>
                            <th class="text-nowrap">Status</th>
                            <th class="text-nowrap">Coupon ID</th>
                            <th class="text-nowrap">Total Amount</th>
                            <th class="text-nowrap">Created At</th>
                            <th class="text-nowrap">Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($paginated_orders as $order): ?>
                            <tr class="text-center">
                                <td><?= htmlspecialchars($order['id']); ?></td>
                                <td><?= htmlspecialchars($order['customer_id']); ?></td>
                                <td><?= htmlspecialchars($order['order_date']); ?></td>
                                <td><?= htmlspecialchars($order['status']); ?></td>
                                <td><?= htmlspecialchars($order['coupon_id']); ?></td>
                                <td><?= htmlspecialchars($order['total_amount']); ?></td>
                                <td class="text-nowrap"><?= htmlspecialchars(date('Y-m-d', strtotime($order['created_at']))); ?></td>
                                <td class="text-nowrap"><?= htmlspecialchars(date('Y-m-d', strtotime($order['updated_at']))); ?></td>
                                <td>
                                    <!-- Add your action buttons here -->
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <nav class="app-pagination">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $current_page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link bg-primary text-white"
                           href="?page=<?= $current_page - 1 ?>&search=<?= urlencode($search_query) ?>" tabindex="-1"
                           aria-disabled="true">Previous</a>
                    </li>
                    <?php for ($page = 1; $page <= $total_pages; $page++): ?>
                        <li class="page-item <?= $page == $current_page ? 'active' : '' ?>">
                            <a class="page-link <?= $page == $current_page ? 'bg-success text-white' : 'bg-light text-dark' ?>"
                               href="?page=<?= $page ?>&search=<?= urlencode($search_query) ?>"><?= $page ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $current_page >= $total_pages ? 'disabled' : '' ?>">
                        <a class="page-link bg-primary text-white"
                           href="?page=<?= $current_page + 1 ?>&search=<?= urlencode($search_query) ?>">Next</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</div>

<?php
require "views/partials/admin_footer.php";
?>
