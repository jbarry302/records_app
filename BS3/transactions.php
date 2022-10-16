<?php
require_once(dirname(__FILE__) . '\config\config.php');
require_once(dirname(__FILE__) . '\config\db.php');

include 'inc/header.php';


if (isset($_POST['submit'])) {
    $info = array('document_code', 'transaction_action', 'office', 'employee', 'remarks');
    if (!array_diff($info, array_keys($_POST))) {
        $date = mysqli_real_escape_string($conn, date("Y-m-d H:i:s"));
        $document_code = mysqli_real_escape_string($conn, $_POST['document_code']);
        $trasaction_action = mysqli_real_escape_string($conn, $_POST['transaction_action']);
        $office = mysqli_real_escape_string($conn, $_POST['office']);
        $employee = mysqli_real_escape_string($conn, $_POST['employee']);
        $remarks = mysqli_real_escape_string($conn, $_POST['remarks']);

        $query = "INSERT INTO transactions(date_log, document_code, action, office, employee, remarks) VALUES('$date', '$document_code', '$trasaction_action', '$office', '$employee', '$remarks')";
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
        die();
    }
}



$limit = 7;
$query_str = "SELECT * FROM transactions LIMIT $limit";


// get maximum available max page
$max_query_str = "SELECT COUNT(*) FROM transactions";
$max_query = mysqli_query($conn, $max_query_str);
$result = mysqli_num_rows($max_query) > 0 ? mysqli_fetch_assoc($max_query) : false;
if ($result) {
    $max_pages = floor($result['COUNT(*)'] / $limit);
}

if (isset($_GET['page'])) {
    // ang value ng page ay hindi lalampas sa max pages na available
    $page = min($max_pages, $_GET['page']);
    $offset = $page * $limit;

    $query_str = "SELECT * FROM transactions LIMIT $limit OFFSET $offset";
} else {
    $_GET['page'] = $page = 0;
}

if (isset($_GET['end'])) {
    // kapag dito ka sa end hanggang max page kalang
    $_GET['page'] = $page = $max_pages;

    // kunin mo sa dulo(desc) ng naka ascending yung id(asc)
    $query_str = "SELECT * FROM (SELECT * FROM transactions ORDER BY id DESC LIMIT $limit) AS RES ORDER BY RES.id ASC";
} else if (isset($_GET['start'])) {
    // kapag nasa start ka, magsisimula ka sa page 0
    $_GET['page'] = $page = 0;
    $query_str = "SELECT * FROM transactions LIMIT $limit";
}



// debuggeristzxc
// echo $query_str .  " with page: $page and a max page of: $max_pages";



$query = mysqli_query($conn, $query_str);
$transactions = mysqli_num_rows($query) > 0 ? mysqli_fetch_all($query, MYSQLI_ASSOC) : [];

?>

<!--  CSS for Demo Purpose, don't include it in your project     -->
<link href="assets/css/transactions.css" rel="stylesheet" />

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

                    <li class="active">
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
                    <li>
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
                            <div class="title">Transactions</div>
                            <div class="description">Here is a subtitle for
                                this table</div>
                        </div>
                        <div class="right">
                            <button type="button" class="btn btn-primary btn-block" data-toggle="modal" data-target="#exampleModalLong">Add
                                New Transaction</button>
                        </div>
                    </div>
                    <div class="table-content">
                        <table>
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>DATELOG</th>
                                    <th>DOCUMENT CODE</th>
                                    <th>ACTION</th>
                                    <th>OFFICE</th>
                                    <th>EMPLOYEE</th>
                                    <th>REMARKS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($transactions as $transaction) : ?>
                                    <tr>
                                        <td><?php echo $transaction['id']; ?></td>
                                        <td><?php echo $transaction['date_log']; ?></td>
                                        <td><?php echo $transaction['document_code']; ?></td>
                                        <td><?php echo $transaction['action']; ?></td>
                                        <td><?php echo $transaction['office']; ?></td>
                                        <td><?php echo $transaction['employee']; ?></td>
                                        <td><?php echo $transaction['remarks']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="table-paginate">
                            <ul>
                                <li>
                                    <a href="<?php echo "transactions.php?start=true"; ?>">
                                        First
                                    </a>
                                </li>
                                <li>
                                    <!-- pwede kalang mag previous from page 0 up to $max_pages baka mamaya palitan mo yung $_GET e haha -->
                                    <a href="<?php $_GET['page'] = max(0, min($_GET['page'], $max_pages) - 1);
                                                echo "transactions.php?page=$_GET[page]"; ?>">
                                        Prev
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php
                                                echo "transactions.php?page=" . min($max_pages, $page + 1) ?>">
                                        Next
                                    </a>
                                </li>
                                <li>
                                    <a href="<?php echo "transactions.php?end=true"; ?>">
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

                    <form action="transactions.php" method="POST">
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Document Code *</label>
                            <select name="document_code" class="form-control" id="exampleFormControlSelect1">
                                <option>100</option>
                                <option>101</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleFormControlSelect1">Action *</label>
                            <select name="transaction_action" class="form-control" id="exampleFormControlSelect1">
                                <option>IN</option>
                                <option>OUT</option>
                                <option>COMPLETE</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Office *</label>
                            <input name="office" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Office">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your data with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Employee *</label>
                            <input name="employee" required type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Employee Name">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your data with anyone else.</small>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Remarks</label>
                            <input name="remarks" type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter Office">
                            <small id="emailHelp" class="form-text text-muted">We'll never share your data with anyone else.</small>
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