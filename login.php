<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>

<h2>Login Page</h2>

<form method="POST">
    Username: <input type="text" name="username" required><br><br>
    Password: <input type="password" name="password" required><br><br>

    <button type="submit" name="doLogin">Login</button>

    <button type="submit" name="showJoins">Show JOINs</button>
</form>

<?php
$conn = new mysqli("localhost","root","","SocialMediaDB");
$message ="";

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function printTable($result) {
    if (!$result || $result->num_rows == 0) {
        echo "<p>No results.</p>";
        return;
    }

    echo "<table border='1' cellpadding='6' cellspacing='0'>";
    echo "<tr>";

    
    $fields = $result->fetch_fields();
    foreach ($fields as $field) {
        echo "<th>" . htmlspecialchars($field->name) . "</th>";
    }
    echo "</tr>";

  
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        foreach ($fields as $field) {
            $col = $field->name;
            echo "<td>" . htmlspecialchars((string)($row[$col] ?? "")) . "</td>";
        }
        echo "</tr>";
    }

    echo "</table>";
}


if (isset($_POST["doLogin"])) {

    $username = $_POST["username"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM Users WHERE username='$username'";
    $result = $conn->query($sql);

    if($result && $result->num_rows > 0){
        $row = $result->fetch_assoc();
        $storedHash = $row["password"];

        if(password_verify($password, $storedHash)){
            $message = "Login Successful";
        } else {
            $message = "Login Unsuccessful";
        }
    } else {
        $message = "Login Unsuccessful";
    }
}


if (isset($_POST["showJoins"])) {

    echo "<hr><h2>JOIN Query Results</h2>";

    //  natural join
    echo "<h3>NATURAL JOIN</h3>";
    $q1 = "SELECT * FROM Users NATURAL JOIN UserDetails;";
    $r1 = $conn->query($q1);
    printTable($r1);

    $cols = "
        Users.username,
        Users.password,
        Users.created_at AS user_created_at,
        UserDetails.full_name,
        UserDetails.email,
        UserDetails.city,
        UserDetails.created_at AS details_created_at
    ";

    // inner join
    echo "<h3>INNER JOIN</h3>";
    $q2 = "SELECT $cols
           FROM Users
           INNER JOIN UserDetails
           ON Users.username = UserDetails.username;";
    $r2 = $conn->query($q2);
    printTable($r2);

    // left outer join
    echo "<h3>LEFT OUTER JOIN</h3>";
    $q3 = "SELECT $cols
           FROM Users
           LEFT JOIN UserDetails
           ON Users.username = UserDetails.username;";
    $r3 = $conn->query($q3);
    printTable($r3);

    //  right outer join
    echo "<h3>RIGHT OUTER JOIN</h3>";
    $q4 = "SELECT $cols
           FROM Users
           RIGHT JOIN UserDetails
           ON Users.username = UserDetails.username;";
    $r4 = $conn->query($q4);
    printTable($r4);

    //  full outer join  
    echo "<h3>FULL OUTER JOIN (UNION simulation)</h3>";
    $q5 = "SELECT $cols
           FROM Users
           LEFT JOIN UserDetails
           ON Users.username = UserDetails.username
           UNION
           SELECT $cols
           FROM Users
           RIGHT JOIN UserDetails
           ON Users.username = UserDetails.username;";
    $r5 = $conn->query($q5);
    printTable($r5);
}
?>

<p style="color:red;">
    <?php echo htmlspecialchars($message); ?>
</p>

</body>
</html>