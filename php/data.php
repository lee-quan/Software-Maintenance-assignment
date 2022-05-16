<?php
// $sql = "SELECT * FROM users WHERE NOT unique_id = {$outgoing_id} ORDER BY user_id DESC";
// $query = mysqli_query($conn, $sql);



$sql2 = "WITH rownum AS (
    SELECT *,ROW_NUMBER() OVER (PARTITION BY u.user_id ORDER BY n.date DESC) AS rn FROM users u LEFT JOIN (SELECT * FROM messages m WHERE m.incoming_msg_id = 1178272901 or m.outgoing_msg_id = 1178272901) n ON n.incoming_msg_id = u.unique_id or n.outgoing_msg_id = u.unique_id WHERE NOT unique_id = 1178272901 ORDER BY n.date DESC, status, user_id DESC
)
SELECT * FROM rownum WHERE rn = 1";
//     $sql2 = "SELECT * FROM users u
// LEFT JOIN 
// (SELECT * FROM messages m WHERE m.incoming_msg_id = {$outgoing_id} or m.outgoing_msg_id = {$outgoing_id}
// ORDER BY date DESC) n
// ON n.incoming_msg_id = u.unique_id or n.outgoing_msg_id = u.unique_id 
// WHERE NOT unique_id = {$outgoing_id} GROUP BY u.user_id ORDER BY status, n.date DESC, user_id DESC";
//     $sql2 = "SELECT * FROM users u
// LEFT JOIN 
// (SELECT * FROM messages m WHERE m.incoming_msg_id = {$row['unique_id']} or m.outgoing_msg_id = {$row['unique_id']}
// ORDER BY date) n
// ON n.incoming_msg_id = u.unique_id or n.outgoing_msg_id = u.unique_id 
// WHERE NOT unique_id = {$outgoing_id} GROUP BY u.unique_id ORDER BY status, n.date DESC, user_id DESC";

//     // $sql2 = "SELECT * FROM messages WHERE (incoming_msg_id = {$row['unique_id']}
//     //             OR outgoing_msg_id = {$row['unique_id']}) AND (outgoing_msg_id = {$outgoing_id} 
//     //             OR incoming_msg_id = {$outgoing_id}) ORDER BY msg_id DESC LIMIT 1";

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
        // $msg_id = 0;
        // $read = 0;
        $yousent = $row2['outgoing_msg_id'];
    }


    if (isset($row2['msg_id'])) {
        if ($read == 0 && $row2['outgoing_msg_id'] != $outgoing_id) {
            $namehighlight =  '<span class="text-primary">' . $row2['fname'] . " " . $row2['lname'] . '</span>';
        } else {
            $namehighlight =  '<span >' . $row2['fname'] . " " . $row2['lname'] . '</span>';
        }
        echo '<a id="' . $row2['unique_id'] . '" href="chat.php?user_id=' . $row2['unique_id'] . '" style="text-decoration: none;">
                        <div class="content">' .
            "<img src='data:" . $row2['img_type'] . ";base64," . $row2['img'] . "' alt='' width=50 height=50/>" . '
                        <div class="details">
                        ' . $namehighlight . '
                            <p>' . $you . $msg . '</p>
                        </div>
                        </div>
                        <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                    </a>';
    } else {
        $namehighlight =  '<span >' . $row2['fname'] . " " . $row2['lname'] . '</span>';
        echo '<a id="' . $row2['unique_id'] . '" href="chat.php?user_id=' . $row2['unique_id'] . '" style="text-decoration: none;">
                        <div class="content">'
            . "<img src='data:" . $row2['img_type'] . ";base64," . $row2['img'] . "' alt=''/>"
            . '<div class="details">
                        ' . $namehighlight . '
                            <p> No message... </p>
                        </div>
                        </div>
                        <div class="status-dot ' . $offline . '"><i class="fas fa-circle"></i></div>
                    </a>';
    }
}
