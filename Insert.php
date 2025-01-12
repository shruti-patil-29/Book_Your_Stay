<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if (!empty($username) && !empty($email) && !empty($phone) && !empty($address)) {
        $host = "localhost";
        $dbUsername = "root";
        $dbPassword = "";
        $dbName = "resorts";

        // Create connection
        $conn = new mysqli($host, $dbUsername, $dbPassword, $dbName);

        if ($conn->connect_error) {
            die('Connect Error (' . $conn->connect_errno . ') ' . $conn->connect_error);
        } else {
            $SELECT = "SELECT email FROM resorts WHERE email = ? LIMIT 1";
            $INSERT = "INSERT INTO resorts (username, email, phone, address) VALUES (?, ?, ?, ?)";

            // Prepare statement
            $stmt = $conn->prepare($SELECT);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            $rnum = $stmt->num_rows;

            if ($rnum == 0) {
                $stmt->close();

                $stmt = $conn->prepare($INSERT);
                $stmt->bind_param("ssss", $username, $email, $phone, $address);
                if ($stmt->execute()) {
                    echo "New record inserted successfully.";
                } else {
                    echo "Error: " . $stmt->error;
                }
            } else {
                echo "Someone already registered using this email.";
            }
            $stmt->close();
            $conn->close();
        }
    } else {
        echo "All fields are required.";
        die();
    }
} else {
    echo "Invalid request method.";
    die();
}
?>
