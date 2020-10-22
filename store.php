<?php
    session_start();
    ob_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- <meta charset="UTF-8"> -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link rel="stylesheet" href="/styles/styles.css">
    <title>Book Store :: Store</title>
    <style>
      .imageModify {
        padding : 5px;
        margin-left: auto;
        margin-right: auto;
        height: 150px;
        width: 200px;
        border-radius: 10%;
      }
      .col-sm-3 {
        padding: 20px;
      }
      .center {
        text-align: center;
        font-weight: bolder;
      }
      .btn-modifier {
        margin-left: auto;
        margin-right: auto;
        width: 60%;
        padding: 5px;
      }
      button {
        background: none;
        border: none;
      }
    </style>
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
    <div class="row">
<?php
        
        require('mysqli_connect.php');
       
        $query = "SELECT * FROM BookInventory WHERE item_quantity >=1";
        $result = @mysqli_query($dbc,$query);
        while($row = mysqli_fetch_array($result)){
        // echo $row["item_name"];
        $a = mysqli_real_escape_string($dbc,$row['item_name']);
        $b = mysqli_real_escape_string($dbc,$row['item_quantity']);
        $image = $row['image_name'];
        $imagestring = "./images/".$image.".png";
        echo "<div class='col-sm-3'>
        <div class='card' style='width: 18rem;'>
        <img  class='card-img-top imageModify' src='$imagestring' alt='Card image cap'>
        <div class='card-body'>
         <b class='center'><h5 class='card-text'>$a</h5></b>
         <form method='POST'>
          <div class='btn-modifier'><button class='btn btn-primary' name='item_name' value='$a'>Checkout Item</button></div>
         </form>
        </div>
      </div>
      </div> 
      <br>";
            }
?>
    </div>
    </main>
</body>
</html>

<?php
  if($_SERVER['REQUEST_METHOD']=='POST'){
    
    $cartitem= $_POST['item_name'];
    $_SESSION['cart_item'] = $cartitem;
    header("Location: checkout.php");
    exit();
  }
?>
