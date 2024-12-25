<!DOCTYPE html>
<html>
<head>
    <?php
    ob_start(); // Start output buffering
    session_start();
    include("includes/db.php");
    if (!isset($_SESSION['customer_email'])) {
        header("Location: customer_login.php");
        exit();
    }
    $user = $_SESSION['customer_email'];
    $query4 = "SELECT sum(p_price) AS total FROM orders WHERE customer_prn='$user'";
    $runQuery4 = mysqli_query($con, $query4);
    $sum = 0;
    if ($row = mysqli_fetch_assoc($runQuery4)) {
        $sum = $row['total'];
    }
    ?>
    <title>Full Menu</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <style type="text/css">
        body {
            background-image: url("images/bg5.jpg");
            background-size: 100%;
        }
    </style>
</head>

<body>
    <div class="navbar-wrapper">
        <div class="navbar navbar-inverse navbar-static-top" role="navigation">
            <h2 align="center" style="color: white">FULL MENU</h2>
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                </button>
                <a class="navbar-brand" href="index.html"></a>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php">Home</a></li>
                        <li class='active'><a href="logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <table cellpadding="20" cellspacing="20" align="center" style="color: white">
            <tr style="margin-left: 2em;" bgcolor="black">
                <th>No.</th>
                <th>Name</th>
                <th>Price</th>
                <th>Add to cart</th>
            </tr>
            <?php
            $i = 0;
            $query = "SELECT * FROM products";
            $runQuery = mysqli_query($con, $query);
            while ($row = mysqli_fetch_assoc($runQuery)) {
                $productName = $row['product_title'];
                $price = $row['product_price'];
                $id = $row['product_id'];
                $i++;
                echo '
                    <tr style="margin-left: 2em;">
                        <td>' . $i . '</td>
                        <td>' . $productName . '</td>
                        <td>' . $price . '</td>
                        <td align="center">
                            <form method="post" action="full_menu.php">
                                <input type="hidden" name="product_id" value="' . $id . '">
                                <button type="submit" name="add_to_cart" class="btn btn-primary">Add to Cart</button>
                            </form>
                        </td>
                    </tr>
                ';
            }
            ?>
        </table><br><br>

        <?php
        if (isset($_POST['add_to_cart'])) {
            $id = $_POST['product_id'];
            $query = "SELECT * FROM products WHERE product_id=" . $id;
            $runQuery = mysqli_query($con, $query);
            if ($row = mysqli_fetch_assoc($runQuery)) {
                $productName = $row['product_title'];
                $price = $row['product_price'];
                $query1 = "INSERT INTO orders (p_id, p_name, p_price, customer_prn) VALUES ('$id', '$productName', '$price', '$user')";
                mysqli_query($con, $query1);
                header("Location: full_menu.php");
                exit();
            }
        }
        ?>

        <center>
            <span>
                <p style="color: white">Current Cart amount:
                    <?php echo $sum ? $sum : 0; ?>
                </p>
            </span>
            <form method="POST" action="formpro.php">
                <input type="submit" class="btn btn-success" value="FINALIZE ORDER" name="sub">
                <input type="hidden" name="hid" value="<?php echo $sum; ?>">
            </form>
        </center>
    </body>
</html>

<?php
ob_end_flush(); // End output buffering and flush output
?>
