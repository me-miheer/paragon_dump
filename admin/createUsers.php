<?php
require_once("checkLogin.php");
require('../connection.php'); // $mysql = mysqli_connect(...)
header('Content-Type: text/html; charset=utf-8');

error_reporting(E_ALL);
ini_set('display_errors', 1);

// Handle AJAX
if (isset($_GET['action'])) {
    $action = $_GET['action'];

    // 🔍 Fetch users with pagination + search
    if ($action == 'getUsers') {
        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit  = 10;
        $offset = ($page - 1) * $limit;
        $search = $_GET['search'] ?? '';

        if ($search != '') {
            $search = mysqli_real_escape_string($mysql, $search);
            $query = "SELECT * FROM user 
                      WHERE name LIKE '%$search%' 
                         OR email LIKE '%$search%' 
                         OR mobile LIKE '%$search%' 
                      LIMIT $offset,$limit";
        } else {
            $query = "SELECT * FROM user LIMIT $offset,$limit";
        }

        $result = mysqli_query($mysql, $query);
        $users = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }

        $totalRes = mysqli_query($mysql, "SELECT COUNT(*) as cnt FROM user");
        $total = mysqli_fetch_assoc($totalRes)['cnt'];

        echo json_encode(['users' => $users, 'total' => $total]);
        exit;
    }

    // 💾 Save user (create/update)
    if ($action == 'saveUser') {
        $id     = $_POST['id'] ?? null;
        $name   = mysqli_real_escape_string($mysql, $_POST['name']);
        $email  = mysqli_real_escape_string($mysql, $_POST['email']);
        $mobile = mysqli_real_escape_string($mysql, $_POST['mobile']);
        $dealer = mysqli_real_escape_string($mysql, $_POST['dealer'] ?? '');
        $retailType = mysqli_real_escape_string($mysql, $_POST['retailType']);
        $shop   = mysqli_real_escape_string($mysql, $_POST['shop']);
        $town   = mysqli_real_escape_string($mysql, $_POST['town']);
        $user_type = mysqli_real_escape_string($mysql, $_POST['user_type'] ?? 'user');

        if ($id) {
            // Update user
            $sql = "UPDATE user SET 
                    name='$name', email='$email', mobile='$mobile',
                    dealer='$dealer', retailType='$retailType', shop='$shop', town='$town', user_type='$user_type'
                    WHERE id=$id";
            mysqli_query($mysql, $sql);
        } else {
            // Create new user
            $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;
            $created_at = time();
            $actual_date = date('Y-m-d');
            $sql = "INSERT INTO user 
                    (name,email,mobile,dealer,retailType,shop,town,password,actual_date,created_at,user_type) 
                    VALUES 
                    ('$name','$email','$mobile','$dealer','$retailType','$shop','$town','$password','$actual_date','$created_at','$user_type')";
            mysqli_query($mysql, $sql);
        }

        echo json_encode(['status' => true]);
        exit;
    }

    // ❌ Delete user
    if ($action == 'deleteUser') {
        $id = (int)$_POST['id'];
        mysqli_query($mysql, "DELETE FROM user WHERE id=$id");
        echo json_encode(['status' => true]);
        exit;
    }

    // 🔑 Reset password
    if ($action == 'resetPassword') {
        $id = (int)$_POST['id'];
        $newPass = password_hash("123456", PASSWORD_DEFAULT);
        mysqli_query($mysql, "UPDATE user SET password='$newPass' WHERE id=$id");
        echo json_encode(['status' => true, 'message' => 'Password reset to 123456']);
        exit;
    }

    // 📤 Export Users to CSV
    if ($action == 'exportCSV') {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=users_export.csv');

        $output = fopen('php://output', 'w');

        // CSV column headers
        fputcsv($output, ['ID', 'Name', 'Email', 'Mobile', 'Dealer', 'Retail Type', 'Shop', 'Town', 'User Type', 'Created At']);

        $query = "SELECT id, name, email, mobile, dealer, retailType, shop, town, user_type, FROM_UNIXTIME(created_at) as created_at FROM user";
        $result = mysqli_query($mysql, $query);

        while ($row = mysqli_fetch_assoc($result)) {
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Manage Users</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container py-4">
        <a class="btn btn-dark" href="index.php"> < Back</a>
        <br><br>
        <h2 class="mb-4">User Management</h2>
        <div class="mb-3">
            <input type="text" id="search" class="form-control" placeholder="Search users...">
        </div>
        <div class="d-flex justify-content-between mb-3">
            <button class="btn btn-primary" onclick="openForm()">+ Create User</button>
            <button class="btn btn-success" onclick="window.location.href='createUsers.php?action=exportCSV'">⬇️ Export Users</button>
        </div>

        <div id="userTable"></div>
        <nav>
            <ul class="pagination" id="pagination"></ul>
        </nav>
    </div>

    <!-- Modal Form -->
    <div class="modal" id="userModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="userForm">
                    <div class="modal-header">
                        <h5 class="modal-title">User Form</h5>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="id" id="userId">
                        <input type="text" name="name" id="name" class="form-control mb-2" placeholder="Name" required>
                        <input type="email" name="email" id="email" class="form-control mb-2" placeholder="Email" required>
                        <input type="text" name="mobile" id="mobile" class="form-control mb-2" placeholder="Mobile" required>

                        <!-- Dealer input -->
                        <input type="text" name="dealer" id="dealer" class="form-control mb-2" placeholder="Dealer / Depot" required>

                        <!-- Retail Type dropdown -->
                        <select name="retailType" id="retailType" class="form-control mb-2">
                            <option value="Retail">Retail</option>
                            <option value="Dealer">Dealer</option>
                        </select>

                        <input type="text" name="shop" id="shop" class="form-control mb-2" placeholder="Shop" required>
                        <input type="text" name="town" id="town" class="form-control mb-2" placeholder="Town" required>

                        <!-- user_type dropdown -->
                        <select name="user_type" id="user_type" class="form-control mb-2">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>

                        <!-- Password only for creation -->
                        <input type="password" name="password" id="password" class="form-control mb-2" placeholder="Password">
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Save</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        let currentPage = 1;

        function loadUsers(page = 1, search = '') {
            $.get("createUsers.php?action=getUsers", {
                page: page,
                search: search
            }, function(res) {
                let data = JSON.parse(res);
                let html = `<table class="table table-bordered"><tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Mobile</th><th>Dealer / Depot</th><th>Shop</th><th>Town</th><th>Retail Type</th><th>User Type</th><th>Action</th></tr>`;
                data.users.forEach(u => {
                    html += `<tr>
                <td>${u.id}</td><td>${u.name}</td><td>${u.email}</td>
                <td>${u.mobile}</td><td>${u.dealer}</td><td>${u.shop}</td><td>${u.town}</td><td>${u.retailType}</td><td>${u.user_type}</td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick='editUser(${JSON.stringify(u)})'>Edit</button>
                    <button class="btn btn-sm btn-danger" onclick='deleteUser(${u.id})'>Delete</button>
                    <button class="btn btn-sm btn-secondary" onclick='resetPassword(${u.id})'>Reset</button>
                </td></tr>`;
                });
                html += `</table>`;
                $("#userTable").html(html);

                let totalPages = Math.ceil(data.total / 10);
                let pagHtml = '';

                if (page > 1) {
                    pagHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadUsers(${page-1}, $('#search').val())">&lt; Prev</a></li>`;
                }

                let startPage = Math.max(1, page - 1);
                let endPage = Math.min(totalPages, page + 1);
                for (let i = startPage; i <= endPage; i++) {
                    pagHtml += `<li class="page-item ${i==page?'active':''}">
                            <a class="page-link" href="#" onclick="loadUsers(${i}, $('#search').val())">${i}</a>
                        </li>`;
                }

                if (page < totalPages) {
                    pagHtml += `<li class="page-item"><a class="page-link" href="#" onclick="loadUsers(${page+1}, $('#search').val())">Next &gt;</a></li>`;
                }

                $("#pagination").html(pagHtml);
                currentPage = page;
            });
        }

        function openForm() {
            $("#userForm")[0].reset();
            $("#userId").val('');
            $("#retailType").val('Retail');
            $("#user_type").val('user');
            $("#password").show();
            new bootstrap.Modal(document.getElementById('userModal')).show();
        }

        function editUser(u) {
            $("#userId").val(u.id);
            $("#name").val(u.name);
            $("#email").val(u.email);
            $("#mobile").val(u.mobile);
            $("#dealer").val(u.dealer || '');
            $("#retailType").val(u.retailType || 'Retail');
            $("#shop").val(u.shop);
            $("#town").val(u.town);
            $("#user_type").val(u.user_type || 'user');
            $("#password").hide(); // hide password field during edit
            new bootstrap.Modal(document.getElementById('userModal')).show();
        }

        $("#userForm").submit(function(e) {
            e.preventDefault();
            $.post("createUsers.php?action=saveUser", $(this).serialize(), function() {
                loadUsers(currentPage);
                bootstrap.Modal.getInstance(document.getElementById('userModal')).hide();
            });
        });

        function deleteUser(id) {
            if (confirm("Delete this user?")) {
                $.post("createUsers.php?action=deleteUser", {
                    id: id
                }, function() {
                    loadUsers(currentPage);
                });
            }
        }

        function resetPassword(id) {
            if (confirm("Reset password to 123456?")) {
                $.post("createUsers.php?action=resetPassword", {
                    id: id
                }, function(res) {
                    alert(JSON.parse(res).message);
                });
            }
        }

        $("#search").on("keyup", function() {
            loadUsers(1, $(this).val());
        });
        $(document).ready(() => loadUsers());
    </script>
</body>

</html>