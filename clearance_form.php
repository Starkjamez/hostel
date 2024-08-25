<?php
    session_start();
    include "includes/dbcontroller.php";
    include "includes/checklogin.php";

    $id = $_SESSION['id'];
    $stmt = $DB_con->prepare("SELECT * FROM userregistration u LEFT JOIN registration r ON u.regNo = r.regno WHERE u.id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $results = $stmt->fetch(PDO::FETCH_ASSOC);

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = $_POST['Fname'];
        $middle_name = $_POST['Mname'];
        $last_name = $_POST['Lname'];
        $student_id = $_POST['student_id'];
        $email = $_POST['student_email'];
        $room_number = $_POST['room_number'];
        $clearance_date = $_POST['clearance_date'];
        $items = $_POST['items'];
        $comments = $_POST['comments'];

        // Insert data into the clearance table
        $stmt = $DB_con->prepare("
            INSERT INTO clearance (
                first_name, middle_name, last_name, student_id, email, room_no, join_date, clearance_date, item_return, comment
            ) VALUES (
                :firstName, :middleName, :lastName, :studentId, :email, :roomNo, :joinDate, :clearanceDate, :itemReturn, :comment
            )
        ");
        $stmt->bindParam(':firstName', $first_name, PDO::PARAM_STR);
        $stmt->bindParam(':middleName', $middle_name, PDO::PARAM_STR);
        $stmt->bindParam(':lastName', $last_name, PDO::PARAM_STR);
        $stmt->bindParam(':studentId', $student_id, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->bindParam(':roomNo', $room_number, PDO::PARAM_STR);
        $stmt->bindParam(':joinDate', $results['regDate'], PDO::PARAM_STR); // Using joinDate from fetched data
        $stmt->bindParam(':clearanceDate', $clearance_date, PDO::PARAM_STR);
        $stmt->bindParam(':itemReturn', $items, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comments, PDO::PARAM_STR);

        if ($stmt->execute()) {
            echo " <SCript>alert('Your clearance will be processed shortly')</SCript>";

        } else {
            echo "<SCript>alert('Error saving clearance data.')</SCript>";
        }
    }
    
?>

<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Clearance Form</title>
    
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/dataTables.bootstrap.min.css">
    <link rel="stylesheet" href="css/bootstrap-social.css">
    <link rel="stylesheet" href="css/bootstrap-select.css">
    <link rel="stylesheet" href="css/fileinput.min.css">
    <link rel="stylesheet" href="css/awesome-bootstrap-checkbox.css">
    <link rel="stylesheet" href="css/style.css">

    <style>
        body {
            display: flex;
        }

        .cls_form {
            margin-top: 100px;
            margin-left: 100px;
            display: flex;
            flex-direction: column;
        }

        .ts-sidebar {
            width: 20vw;
        }

        .ts-sidebar-menu {
            width: 20vw;
        }
    </style>
    
</head>
<body>
    <?php include "includes/header.php"; ?>
    <?php include "includes/sidebar.php"; ?>
    
    <h1>Hostel Clearance Form</h1>
    <form action="clearance_form.php" method="post" class="cls_form">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        
        <div class="names">
            <label for="Fname">First Name</label>
            <input type="text" id="Fname" name="Fname" value="<?php echo htmlspecialchars($results['firstName']); ?>" required>

            <label for="Mname">Middle Name:</label>
            <input type="text" id="Mname" name="Mname" value="<?php echo htmlspecialchars($results['middleName']); ?>" required>

            <label for="Lname">Last Name:</label>
            <input type="text" id="Lname" name="Lname" value="<?php echo htmlspecialchars($results['lastName']); ?>" required>
        </div>
        
        <label for="student_id">Student ID:</label>
        <input type="text" id="student_id" name="student_id" value="<?php echo htmlspecialchars($results['regNo']); ?>" required>

        <label for="student_email">Student Email:</label>
        <input type="email" id="student_email" name="student_email" value="<?php echo htmlspecialchars($results['email']); ?>" required>

        <label for="room_number">Room Number:</label>
        <input type="text" id="room_number" name="room_number" value="<?php echo htmlspecialchars($results['roomno']); ?>" required>

        <div class="dates">
            <label for="clearance_date">Joining Date:</label>
            <span><?php echo htmlspecialchars($results['regDate']); ?></span>

            <label for="clearance_date">Clearance Date:</label>
            <input type="date" id="clearance_date" name="clearance_date" required>
        </div>

        <label for="items">Items Returned:</label>
        <textarea id="items" name="items" rows="4" cols="50" placeholder="List items returned, if any"></textarea>

        <label for="comments">Comments:</label>
        <textarea id="comments" name="comments" rows="4" cols="50" placeholder="Additional comments"></textarea>

        <input type="submit" value="CLEAR">
    </form>

</body>
</html>
