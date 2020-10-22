<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        .alert {
            margin-left: auto;
            margin-right: auto;
            width: 30%;
        }
        .centermodify{
            display: flex;
            justify-content: center;
        }
        form {
            margin-left: auto;
            margin-right: auto;
            width: 30%;
        }
        select {
            margin-left : auto;
            margin-right : auto;
        }

    </style>
    <title>Book Store :: Checkout</title>
</head>
<body>
    <header>

    </header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.html">BookStore</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav">
            <li class="nav-item active">
              <a class="nav-link" href="index.html">Home <span class="sr-only">(current)</span></a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="store.php">Store</a>
            </li>
           
          </ul>
        </div>
      </nav>
    <main>
        <?php
        session_start();
        $quantity = 0;
        // echo $_SESSION['cart_item'];
        require('mysqli_connect.php');
        $i = $_SESSION['cart_item'];
        $query = "SELECT * FROM BookInventory WHERE item_name='$i'";
        $res = mysqli_query($dbc,$query);
        while($row = mysqli_fetch_array($res)){
            // echo $row["item_name"];
            $item = mysqli_real_escape_string($dbc,$row['item_name']);
            $image = $row['image_name'];
            $item_price = $row['price'];
            $imagestring = "./images/".$image.".png";
            $quantity = $row['item_quantity'];
            echo "<p class='centermodify'>These are the details of your item:<br>
                     Item name : $item <br>
                     item Price : $item_price </p>";
        }
        ?>

        
<?php
    $error = false;
    $fname = "";
    $lname = "";
    $email = "";
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $cart = $_SESSION['cart_item'];
        
        $fname = strip_tags($_POST['firstname']);
        $lname = strip_tags($_POST['lastname']);
        $email = strip_tags($_POST['email']);
        // $payment = $_POST['payment'];
        // echo $payment;
        if(empty(trim($_POST['firstname']))){
            $error = true;
            echo "<div class='alert alert-primary' role='alert'>
           <span class='centermodify'> Please enter a valid First Name </span>
          </div>";
        }
        else if(empty(trim($_POST['lastname']))){
            $error=true;
            echo "<div class='alert alert-primary' role='alert'>
            <span class='centermodify'> Please enter a valid Last Name</span>
          </div>";
        }
        else if(empty(trim($_POST['email']))){
            $error = true;
            echo "<div class='alert alert-primary' role='alert'>
            <span class='centermodify'> Please enter a valid Email Address</span>
          </div>";
        }
        else if(empty($_POST['payment'])){
            $error = true;
            echo "<div class='alert alert-primary' role='alert'>
            <span class='centermodify'> Please enter a valid Payment Method</span>
          </div>";
        } 
        else if($_POST['quantityselected']=="Choose Quantity"){
            $error = true;
            echo "<div class='alert alert-primary' role='alert'>
            <span class='centermodify'> Please enter a valid Quantity</span>
          </div>";
        }
        else {
            // require('mysqli_connect.php');
            $flag = true;
            $id = 0;
            mysqli_autocommit($dbc,false);
            $query1 = "SELECT id FROM BookInventory WHERE item_name='$cart'";


            $result = mysqli_query($dbc,$query1);

            if(!$result){
                $flag = false;
                echo "Error details : " . mysqli_error($dbc);
            }

            while($row = mysqli_fetch_array($result)){
                echo $row['id'];
                $id = $row['id'];
            }

            $quantity = $_POST['quantityselected'];
            $query2 = "UPDATE BookInventory SET item_quantity=item_quantity-'$quantity' WHERE id='$id'";
           
            $result2 = mysqli_query($dbc,$query2);

            if(!$result2){
                $flag = false;
                echo "Error details : " . mysqli_error($dbc);
            }

            $payment = $_POST['payment'];
            $query3 = "INSERT INTO BookInventoryOrder VALUES(NULL,'$fname','$lname','$email','$payment','$id')";

            $result3 = mysqli_query($dbc,$query3);
            if(!$result3){
                $flag = false;
                echo "Error details : " . mysqli_error($dbc);
            }
            
            if($flag){
                mysqli_commit($dbc);
                echo "<div class='alert alert-success' role='alert'>
                <span class='centermodify'> Congratulations on your order.</span>
              </div>";
            }
            else {
                mysqli_rollback($dbc);
                echo "all queries are rolled back";
            }
            mysqli_close($dbc);
        }
        
        
    }
?>
       
        <form method="POST">
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" value="<?php echo $fname; ?>" >
            </div>

            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" value="<?php echo $lname; ?>">
            </div>
            
            <div class="form-group">
                <label for="">Email address</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" value="<?php echo $email; ?>">
            </div>
            <?php
                $quantityarray = array();
                for($x=1;$x <=$quantity;$x++){
                    array_push($quantityarray,$x);
                }
            ?>
            <select class="centermodify" name="quantityselected">
            
            <option selected="selected">Choose Quantity</option>
            <?php
                foreach($quantityarray as $quant){
            ?>
            <option value="<?php echo $quant; ?>"><?php echo $quant; ?></option>
            <?php    
                }
            ?>
        </select>
            <label for="payment options">Payment Options</label><br>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment" id="inlineRadio1" value="Visa">
                <label class="form-check-label" for="inlineRadio1">Visa</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="payment" id="inlineRadio2" value="Debit">
                <label class="form-check-label" for="inlineRadio2">Debit</label>
            </div>
            <div style="text-align: center; padding: 5px;"><button type="submit" class="btn btn-primary">Submit</button></div>
        </form>
    </main>
    <footer>

    </footer>
</body>
</html>
