
<?php
$connect = mysqli_connect("localhost", "root", "", "banking system") or die("Connection Failed");
$sender = $_GET['id']; //Sender ID 
$senderbalanceq = "SELECT Balance from Users WHERE UID=$sender";
$senderbalancer = mysqli_query($connect,$senderbalanceq);

foreach($senderbalancer as $r)
{
    $senderbalance = $r['Balance']; //Sender's Balance;  
}


$query1= "SELECT * from Users WHERE UID=$sender"; //Select user's data that wants to make transfer 
$result1= mysqli_query($connect,$query1);
$count1 = mysqli_num_rows($result1);

$query2="SELECT * from Users WHERE UID!=$sender"; //For dropdown of transfer to 
$result2= mysqli_query($connect,$query2);
$count2 = mysqli_num_rows($result2);

if (isset($_POST['Submit']))
{

    $receiver= $_POST['Receiver']; //Returns ID of receiver 
    $receiverbalanceq = "SELECT Balance from Users WHERE UID=$receiver";
    $receiverbalancer = mysqli_query($connect,$receiverbalanceq);
    foreach($receiverbalancer as $r)
    {

        $receiverbalance = $r['Balance']; //Receiver's Balance
    }


    $amount = $_POST['Amount']; // Returns Amount in field 
    
    if ($amount<0)
    {
        echo "<script>alert('Negative value cannot be transferred!');</script>";
    }

    else if ($amount==0)
    {
        echo "<script>alert('Zero cannot be transferred!');</script>";
    }

    else if ($amount > $senderbalance )
    {
        echo "<script>alert('Insufficient amount!');</script>";
    }

    else 
    {
       
          $senderbalance = $senderbalance-$amount; //Sender's new balance after transfer
          //echo $senderbalance;
            
          $receiverbalance = $receiverbalance+$amount; //Receiver's new balance after transfer
         //echo $receiverbalance; 
       
       
        //UPDATE DATABASE for users table
        $senderquery = "UPDATE Users SET Balance = '$senderbalance' WHERE UID=$sender";
        $receiverquery = "UPDATE Users SET Balance='$receiverbalance' WHERE UID=$receiver";
        $senderexec = mysqli_query($connect,$senderquery); //execution of query
        $receiverexec= mysqli_query($connect,$receiverquery); //execution of query

        //ADD in DATABASE for transcation table

        //Sender's name
        $sendernameq = "SELECT Name from Users WHERE UID=$sender";
        $sendernamer = mysqli_query($connect,$sendernameq);
        foreach($sendernamer as $r)
        {
                $sendername = $r['Name']; //Sender's Name
        }

        //Receiver's name
        $receivernameq = "SELECT Name from Users WHERE UID=$receiver";
        $receivernamer = mysqli_query($connect,$receivernameq);
        foreach($receivernamer as $r)
        {
            $receivername = $r['Name']; //Receiver's Name
        }

        $transactionquery= "INSERT INTO Transaction (`Sender`, `Receiver`, `Balance`)
        VALUES ('$sendername', '$receivername', '$amount')";
        $transactionexec = mysqli_query($connect,$transactionquery); //Execution of query


        echo "<script>alert('Transaction was successful!');
                      window.location = 'TransactionHistory.php';
        </script>";
        
    }
    $senderbalance=0;
    $receiverbalance=0;
    $amount=0;

}

?>


<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make A Transfer</title>
    
    <!--Google fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Caprasimo">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Cinzel">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Ruda">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Markazi Text">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Mitr">

    <!--Icon-->
    <link rel="icon" href="images/Icon.png" >

    <!--Imports-->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
  
  
  
  <style>

      *
      {
          padding:0;
          margin: 0;
          box-sizing:border-box;
      }


      /************************/


        /*Transaction*/

        .table-heading {
          font-size: 32px;
          font-weight: bold;
          text-align: center;
          margin-top:120px;
          color:#5b828e;
          font-family:'Mitr';
        }
        .responstable {
          margin: 100px 0;
          width: 100%;
          overflow: hidden;
          background: #FFF;
          color: #024457;
          align-items:center;
          border: 1px solid black;
          border-radius: 10px;
        }
        .responstable tr {
          border: 1px solid #D9E4E6;
        }
        .responstable tr:nth-child(odd) {
          background-color: #EAF3F3;
        }
        .responstable th {
          display: none;
          border: 1px solid #FFF;
          background-color: #167F92;
          color: #FFF;
          padding: 1em;
        }
        .responstable th:first-child {
          display: table-cell;
          text-align: center;
        }
        .responstable th:nth-child(2) {
          display: table-cell;
        }
        .responstable th:nth-child(2) span {
          display: none;
        }
        .responstable th:nth-child(2):after {
          content: attr(data-th);
        }
        @media (min-width: 480px) {
          .responstable th:nth-child(2) span {
            display: block;
          }
          .responstable th:nth-child(2):after {
            display: none;
          }
        }
        .responstable td {
          display: block;
          word-wrap: break-word;
          max-width: 7em;
        }
        .responstable td:first-child {
          display: table-cell;
          text-align: center;
          border-right: 1px solid #D9E4E6;
        }
        @media (min-width: 480px) {
          .responstable td {
            border: 1px solid #D9E4E6;
          }
        }
        .responstable th, .responstable td {
          text-align: left;
          margin: .5em 1em;
        }
        @media (min-width: 480px) {
          .responstable th, .responstable td {
            display: table-cell;
            padding: 1em;
          }
        }


        /************************/

        /*Footer*/

        footer {
            background: #16222A;
            background: -webkit-linear-gradient(59deg, #3A6073, #16222A);
            background: linear-gradient(59deg, #3A6073, #16222A);
            color: white;
            margin-top:100px;
          }
          
          footer a {
            color: #fff;
            font-size: 14px;
            transition-duration: 0.2s;
          }
          
          footer a:hover {
            color: #FA944B;
            text-decoration: none;
          }
          
          .copy {
            font-size: 12px;
            padding: 10px;
            border-top: 1px solid #FFFFFF;
          }
          
          .footer-middle {
            padding-top: 2em;
            color: white;
          }
          
          
          /*SOCİAL İCONS*/
          
          /* footer social icons */
          
          ul.social-network {
            list-style: none;
            display: inline;
            margin-left: 0 !important;
            padding: 0;
          }
          
          ul.social-network li {
            display: inline;
            margin: 0 5px;
          }
          
          
          /* footer social icons */
          
          .social-network a.icoFacebook:hover {
            background-color: #3B5998;
          }
          
          .social-network a.icoLinkedin:hover {
            background-color: #007bb7;
          }
          
          .social-network a.icoFacebook:hover i,
          .social-network a.icoLinkedin:hover i {
            color: #fff;
          }
          
          .social-network a.socialIcon:hover,
          .socialHoverClass {
            color: #44BCDD;
          }
          
          .social-circle li a {
            display: inline-block;
            position: relative;
            margin: 0 auto 0 auto;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
            text-align: center;
            width: 30px;
            height: 30px;
            font-size: 15px;
          }
          
          .social-circle li i {
            margin: 0;
            line-height: 30px;
            text-align: center;
          }
          
          .social-circle li a:hover i,
          .triggeredHover {
            -moz-transform: rotate(360deg);
            -webkit-transform: rotate(360deg);
            -ms--transform: rotate(360deg);
            transform: rotate(360deg);
            -webkit-transition: all 0.2s;
            -moz-transition: all 0.2s;
            -o-transition: all 0.2s;
            -ms-transition: all 0.2s;
            transition: all 0.2s;
          }
          
          .social-circle i {
            color: #595959;
            -webkit-transition: all 0.8s;
            -moz-transition: all 0.8s;
            -o-transition: all 0.8s;
            -ms-transition: all 0.8s;
            transition: all 0.8s;
          }
          
          .social-network a {
            background-color: #F9F9F9;
          }



/************************/

  </style>

</head>


<body>
<!--Header-->
  <header>
      <nav class="navbar navbar-expand-sm fixed-top navbar-light" style="position:absolute;">
          <div class="container">
            <b> <a class="navbar-brand" href="HomePage.html" style="font-size: 35px; color:#5b828e; font-family:'Cinzel';">Citi Bank</a> </b> 
        <div class="btn-group dropleft">
          <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="color:white;background:#5b828e;">
            Services
          </button>
          <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <a class="dropdown-item" href="Customers.php">View All Customers</a>
            <a class="dropdown-item" href="Transaction.php">Make A Transaction</a>
            <a class="dropdown-item" href="TransactionHistory.php">Transaction History</a>
          </div>
        </div>
        </div>
      </nav>  
  </header>


<!--Transaction-->
<div class="table-heading">MONEY TRANSFER</div>
<table class="responstable">
      <tr>
            <th>User ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Balance</th>
      </tr>
        <?php
          if ($count1>0)
          {
          ?>
          <?php
          ?> 
          <?php
          foreach($result1 as $r)
          {
          ?>
            <tr>
                <td style="border: 1px solid #D9E4E6;border-bottom:2px solid #D9E4E6;"> <?php echo $r['UID']?></td>
                <td style="border: 1px solid #D9E4E6;border-bottom:2px solid #D9E4E6;"> <?php echo $r['Name']?></td>
                <td style="border: 1px solid #D9E4E6;border-bottom:2px solid #D9E4E6;"> <?php echo $r['Email']?></td>
                <td style="border: 1px solid #D9E4E6;border-bottom:2px solid #D9E4E6;"> <?php echo $r['Balance']?></td>
          <?php
          }
          ?>
          <?php
        }
        ?>
 </table>





<form method="post" action="" >      
        <div class="form-group">
            <label for="Transaction" style="text-align:left;font-family:'Markazi Text';font-size:25px;margin-left:2%;">Tranfer to:</label>
            <select name="Receiver" class="form-control" id="Transaction" style="margin-bottom:2%;">
                <?php
                        if ($count2>0)
                        {
                        ?>
                        <?php
                        ?> 
                        <?php
                        foreach($result2 as $r)
                        {
                        ?>
                        <option class="table" value="<?php echo $r['UID'];?>">
                                    
                                Name: <?php echo $r['Name'] ;?> , Balance:
                                    <?php echo $r['Balance'] ;?> 
                        </option>
                        <?php
                        }
                        ?>
                        <?php
                        }
                        ?>
            </select>
        </div>
       <label for="amount" style="text-align:left;font-family:'Markazi Text';font-size:25px;margin-left:2%;">Amount:</label>
        <input type="number" name="Amount" class="form-control form-control-sm" required style="width:30%;">  
      <button name="Submit" type="Submit" class="btn btn-primary btn-lg" style="display:block;margin:auto;background:#5b828e;color:white;" >Transfer</button>
</form>


    

      

<!--Footer-->
<footer class="mainfooter" role="contentinfo">
  <div class="footer-middle">
  <div class="container">
    <h5 style="font-size:32px;">CITI Bank</h5>
    <div class="row">
      <div class="col-md-3 col-sm-6">
        <!--Column1-->
        <div class="footer-pad">
          <h4></h4>
          <ul class="list-unstyled">
            <li><a href="#"></a></li>
            <li><a href="#">Payment Center</a></li>
            <li><a href="#">Contact Directory</a></li>
            <li><a href="#">Forms</a></li>
            <li><a href="#">News and Updates</a></li>
            <li><a href="#">FAQs</a></li>
          </ul>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <!--Column1-->
        <div class="footer-pad">
          <h4></h4>
          <ul class="list-unstyled">
            <li><a href="#">Website Tutorial</a></li>
            <li><a href="#">Accessibility</a></li>
            <li><a href="#">Disclaimer</a></li>
            <li><a href="#">Privacy Policy</a></li>
            <li><a href="#">FAQs</a></li>
            <li><a href="#">Webmaster</a></li>
          </ul>
        </div>
      </div>
      <div class="col-md-3 col-sm-6">
        <!--Column1-->
        <div class="footer-pad">
          <h4></h4>
          <ul class="list-unstyled">
            <li><a href="#">Parks and Recreation</a></li>
            <li><a href="#">Public Works</a></li>
            <li><a href="#">Police Department</a></li>
            <li><a href="#">Fire</a></li>
            <li><a href="#">Mayor and City Council</a></li>
            <li>
              <a href="#"></a>
            </li>
          </ul>
        </div>
      </div>
    	<div class="col-md-3">
    		<h4>Follow Us</h4>
            <ul class="social-network social-circle">
             <li><a href="#" class="icoFacebook" title="Facebook"><i class="fab fa-facebook"></i></a></li>
             <li><a href="#" class="icoLinkedin" title="Linkedin"><i class="fab fa-linkedin"></i></a></li>
            </ul>				
		</div>
    </div>
	<div class="row">
		<div class="col-md-12 copy">
			<p class="text-center">&copy; Copyright 2023 - CITI Bank.  All rights reserved.</p>
		</div>
	</div>
  </div>
  </div>
</footer>



  

</body>

</html>
