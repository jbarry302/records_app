<?php
require_once(dirname(__FILE__) . '\config\config.php');
require_once(dirname(__FILE__) . '\config\db.php');
include 'inc/header.php';


if (isset($_POST['submit'])) {
    $info = array('last_name', 'first_name', 'address', 'office');
    if (!array_diff($info, array_keys($_POST))) {
        $lastname = mysqli_real_escape_string($conn, $_POST['last_name']);
        $firstname = mysqli_real_escape_string($conn, $_POST['first_name']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $office = mysqli_real_escape_string($conn, $_POST['office']);
        
        $query = "INSERT INTO employees(last_name, first_name, address, office) VALUES('$lastname', '$firstname', '$address', '$office')";

        if (mysqli_query($conn, $query)) {
            echo '
                <div class="insertsuccess" style="background:green; text-align: center; padding: 5px; color: white;">
                Transaction Added
                </div>
            ';
        } else {
            echo '
                <div class="inserfailed" style="background:red; text-align: center; padding: 5px; color: white;">
                Failed to Add Transaction
                </div>
            ';
        }
    } else {
        echo "Error: Missing required fields";
        echo json_encode($_POST);
        // die();
    }
}


$limit = 7;
$query_str = "SELECT * FROM employees LIMIT $limit";


// get maximum available max page
$max_query_str = "SELECT COUNT(*) FROM employees";
$max_query = mysqli_query($conn, $max_query_str);
$result = mysqli_num_rows($max_query) > 0 ? mysqli_fetch_assoc($max_query) : false;
if ($result) {
    $max_pages = floor($result['COUNT(*)'] / $limit);
}

if (isset($_GET['page'])) {
    // ang value ng page ay hindi lalampas sa max pages na available
    $page = min($max_pages, $_GET['page']);
    $offset = $page * $limit;

    $query_str = "SELECT * FROM employees LIMIT $limit OFFSET $offset";
} else {
    $_GET['page'] = $page = 0;
}

if (isset($_GET['end'])) {
    // kapag dito ka sa end hanggang max page kalang
    $_GET['page'] = $page = $max_pages;

    // kunin mo sa dulo(desc) ng naka ascending yung id(asc)
    $query_str = "SELECT * FROM (SELECT * FROM employees ORDER BY id DESC LIMIT $limit) AS RES ORDER BY RES.id ASC";
} else if (isset($_GET['start'])) {
    // kapag nasa start ka, magsisimula ka sa page 0
    $_GET['page'] = $page = 0;
    $query_str = "SELECT * FROM employees LIMIT $limit";
}



// debuggeristzxc
// echo $query_str .  " with page: $page and a max page of: $max_pages";

$query = mysqli_query($conn, $query_str);
$employees = mysqli_num_rows($query) > 0 ? mysqli_fetch_all($query, MYSQLI_ASSOC) : [];

?>

<!--  CSS for Demo Purpose, don't include it in your project     -->
<link href="assets/css/user.css" rel="stylesheet" />

<!-- <link href="style/styles.css" rel="stylesheet" /> -->


<!--     Fonts and icons     -->
<link href="http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
<link href="assets/css/pe-icon-7-stroke.css" rel="stylesheet" />

</head>

<body onload="removeNotif()">

    <div class="wrapper">
        <div class="sidebar" data-color="purple" data-image="assets/img/sidebar-5.jpg">

            <div class="sidebar-wrapper">
                <div class="logo">
                    <a href="#" class="simple-text">
                        YOUR LOGO
                    </a>
                </div>
                <ul class="nav">

                    <li>
                        <a href="transactions.php">
                            <i class="pe-7s-note2"></i>
                            <p>TRANSACTIONS</p>
                        </a>
                    </li>
                    <li>
                        <a href="office.php">
                            <i class="pe-7s-drawer"></i>
                            <p>OFFICE</p>
                        </a>
                    </li>
                    <li class="active">
                        <a href="user.php">
                            <i class="pe-7s-user"></i>
                            <p>USER</p>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="main-panel">
            <nav class="navbar navbar-default navbar-fixed">
                <div class="container-fluid">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                        <a class="navbar-brand" href="#">Dashboard</a>
                    </div>
                    <div class="collapse navbar-collapse">
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="#">
                                    <p>Log out</p>
                                </a>
                            </li>
                            <li class="separator hidden-lg"></li>
                        </ul>
                    </div>
                </div>
            </nav>

            <div class="content">
                <div class="inner-content">
                    <div class="table-header">
                        <div class="left">
                            <div class="title">Employees</div>
                            <div class="description">Here is a subtitle for
                                this table</div>
                        </div>
                        <div class="right">
                            <button type="button" class="btn btn-primary btn-block"  data-toggle="modal" data-target="#exampleModalLong">Add
                                New Employee</button>
                        </div>
                    </div>
                    <div class="table-content">
                        <table>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>LAST NAME</th>
                                    <th>FIRST NAME</th>
                                    <th>ADDRESS</th>
                                    <th>OFFICE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($employees as $employee) : ?>
                                    <tr>
                                        <td><?php echo $employee['id']; ?></td>
                                        <td><?php echo $employee['last_name']; ?></td>
                                        <td><?php echo $employee['first_name']; ?></td>
                                        <td><?php echo $employee['address']; ?></td>
                                        <td><?php echo $employee['office']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="table-paginate">
                            <ul>
                                <li>
                                    <a href="<?php echo "user.php?start=true"; ?>">
                                        First
                                    </a>
                                </li>
                                <li>
                                    <!-- pwede kalang mag previous from page 0 up to $max_pages baka mamaya palitan mo yung $_GET e haha -->
                                    <a href="<?php $_GET['page'] = max(0, min($_GET['page'], $max_pages) - 1);
                                                echo "user.php?page=$_GET[page]"; ?>">
                                        Prev
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php
                                                echo "user.php?page=" . min($max_pages, $page + 1) ?>">
                                        Next
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo "user.php?end=true"; ?>">
                                        Last
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            </div>

            <footer class="footer">
                <p class="copyright pull-right">
                    &copy; <script>
                        document.write(new Date().getFullYear())
                    </script>
                    <a href="http://www.creative-tim.com">Creative Tim</a>,
                    made with love for a better web
                </p>
        </div>
        </footer>

    </div>
    </div>


    <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Transaction</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                    <form action="user.php" method="POST">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Last Name *</label>
                            <input name="last_name" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Last Name">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your data with anyone else</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">First Name *</label>
                            <input name="first_name" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter First Name">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your data with anyone else</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Address *</label>
                            <input name="address" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Address">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your data with anyone else</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Office *</label>
                            <input name="office" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Office">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your data with anyone else</small>
                        </div>
                        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
                    </form>


                </div>
            </div>
        </div>
    </div>


</body>


<script>
    function removeNotif() {
        const hasNotif = document.querySelector('.inserfailed,.insertsuccess');
        if (hasNotif.attributes.length > 0) {
            console.log('has success');
            setTimeout(() => {
                hasNotif.style.display = 'none';
            }, 4000);
        }
    }
</script>

<?php include 'inc/footer.php'; ?>