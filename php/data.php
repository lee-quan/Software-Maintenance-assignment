<?php
$sql3 = "SELECT * FROM users WHERE unique_id = {$outgoing_id} ORDER BY user_id DESC";
$query3 = mysqli_query($conn, $sql3);
$qeury_lock = $query3;
$row = $qeury_lock->fetch_assoc();
$lock_query = $row['lock'];
$lock_remove_space = str_replace(' ', '', $lock_query);
$lock_arr = explode("|", $lock_remove_space); //all the user that this user has lock

$sortArr = [];
$user_id = $_SESSION['user_id'];
$folder    = "../labeled_images/$user_id";
$entry = true;
//check if any folder exists, if no make a new directory

if (file_exists($folder)) {
    $path    = $folder;
    $files = array_diff(scandir($path), array('.', '..'));
    if (sizeof($files) < 3) {
        $entry = false;
    }
} else {
    $entry = false;
}

$sql2 = "SELECT * FROM users u
    LEFT JOIN 
    (SELECT * FROM messages m WHERE m.incoming_msg_id = {$row['unique_id']} or m.outgoing_msg_id = {$row['unique_id']}
    ORDER BY date) n
    ON n.incoming_msg_id = u.unique_id or n.outgoing_msg_id = u.unique_id 
    WHERE NOT unique_id = {$outgoing_id} GROUP BY u.unique_id ORDER BY status, n.date DESC, user_id DESC";
// echo $sql2;
// $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
//             OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
//             OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";

$query2 = mysqli_query($conn, $sql2);
while ($row2 = mysqli_fetch_assoc($query2)) {
    (mysqli_num_rows($query2) > 0) ? $result = $row2['msg'] : $result = "No message available";
    (strlen($result) > 28) ? $msg =  substr($result, 0, 28) . '...' : $msg = $result;
    if (isset($row2['outgoing_msg_id'])) {
        ($outgoing_id == $row2['outgoing_msg_id']) ? $you = "You: " : $you = "";
    } else {
        $you = "";
    }

    ($row2['status'] == "Offline now") ? $offline = "offline" : $offline = "";
    if (mysqli_num_rows($query2) > 0) {
        $msg_id = $row2['msg_id'];
        $read = $row2['read'];
        $yousent = $row2['outgoing_msg_id'];
    } else {
        $msg_id = 0;
        $read = 0;
        $yousent = $row2['outgoing_msg_id'];
    }

    if ($read == 0 && $yousent != $outgoing_id && $result != "No message available") {
        $namehighlight =  '<span class="text-primary">' . $row2['fname'] . " " . $row2['lname'] . '</span>';
    } else {
        $namehighlight =  '<span >' . $row2['fname'] . " " . $row2['lname'] . '</span>';
    }

    echo '<a id="' . $row2['unique_id'] . '" href="chat.php?user_id=' . $row2['unique_id'] . '" style="text-decoration: none;">
                        <div class="content">
                        <img src="php/images/' . $row2['img'] . '" alt="">
                        <div class="details">
                        ' . $namehighlight . '
                            <p>' . $you . $msg . '</p>
                        </div>
                        </div>
                        <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                    </a>';
}
