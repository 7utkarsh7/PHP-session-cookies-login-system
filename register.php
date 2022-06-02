<?php
// Include config file
require_once "config.php";
 
// Define variables and initialize with empty values
$username = $password = $confirm_password = $email= $country= $state = $pincode ="";
$username_err = $password_err = $confirm_password_err = $email_err = $country_err = $state_err=$pin_err="";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate username
    if(empty(trim($_POST["username"]))){
        $username_err = "Please enter a username.";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["username"]))){
        $username_err = "Username can only contain letters, numbers, and underscores.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM users WHERE username = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
            // Set parameters
            $param_username = trim($_POST["username"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    //validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";     
    } else{
        $email = trim($_POST["email"]);
    }

    //validate country
    if(empty(trim($_POST["country"]))){
        $country_err = "Please enter a country.";     
    } else if(!preg_match('/^[a-zA-Z_]+$/', trim($_POST["country"]))){
        $country_err = "Please enter a valid country name.";     
    }else{
        $country = trim($_POST["country"]);
    }

    //validate state
    if(empty(trim($_POST["state"]))){
        $state_err = "Please enter a state.";     
    } else if(!preg_match('/^[a-zA-Z_]+$/', trim($_POST["state"]))){
        $state_err = "Please enter a valid state name.";     
    }else{
        $state = trim($_POST["state"]);
    }
    //validate pincode
    if(empty(trim($_POST["pincode"]))){
        $pin_err = "Please enter a pincode.";     
    } else if(!preg_match('/^[0-9_]+$/', trim($_POST["pincode"]))){
        $pin_err = "Please enter a valid pincode.";     
    }else{
        $pincode = trim($_POST["pincode"]);
    }

    // Check input errors before inserting in database
    if(empty($username_err) && empty($password_err) && empty($confirm_password_err) && empty($email_err)&& empty($country_err)&&empty($state_err)&&empty($pin_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (username, password, email, country,state, pin) VALUES (?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_username, $param_password, $param_email, $param_country,
                                    $param_state,$param_pin);
            
            // Set parameters
            $param_username = $username;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_email=$email;
            $param_country=$country;
            $param_state=$state;
            $param_pin=$pincode;

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body{ font: 14px sans-serif; background-color:#e5e5e5;}
        .wrapper{ width: 360px; padding: 20px; margin-left: 35%; text-align: center;}
        .form-group{
            text-align: left;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($username_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $username; ?>">
                <span class="invalid-feedback"><?php echo $username_err; ?></span>
            </div>    
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                <span class="invalid-feedback"><?php echo $password_err; ?></span>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
            </div>
            <div class="form-group">
                <label>E-mail</label>
                <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                <span class="invalid-feedback"><?php echo $email_err; ?></span>
            </div>
            <div class="form-group">
                <label for="country">Country</label>
                <select name="country" class="form-control <?php echo (!empty($country_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $country; ?>">
	               <option value="">--- Choose a <label for="country">Country</label> ---</option>
	               <option value="India">India</option>
                </select>
                <span class="invalid-feedback"><?php echo $country_err; ?></span>
            </div>
            <div class="form-group">
                <label>State</label>
                <select  name="state" class="form-control <?php echo (!empty($state)) ? 'is-invalid' : ''; ?>" value="<?php echo $state; ?>">
                <option value="">--- Choose a <label for="state">State</label> ---</option>
	               <option value="UttarPradesh">UttarPradesh</option>
                   <option value="MadhyaPradesh">MadhyaPradesh</option>
                   <option value="AndhraPradesh">AndhraPradesh</option>
                </select>
                <span class="invalid-feedback"><?php echo $state_err; ?></span>
            </div>
            <div class="form-group">
                <label>Pincode</label>
                <input type="text" name="pincode" class="form-control <?php echo (!empty($pin_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $pincode; ?>">
                <span class="invalid-feedback"><?php echo $pin_err; ?></span>
            </div>
            <div class="form-group">
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-secondary ml-2" value="Reset">
            </div>
            
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>