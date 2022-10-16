<?php

generate_data();
// reset_data();

function generate_data()
{
    require('vendor/autoload.php');
    require_once(dirname(__FILE__) . '\..\config\config.php');
    require_once(dirname(__FILE__) . '\..\config\db.php');
    $faker = Faker\Factory::create();

    $test = true;

    for ($i = 0; $i < 200; $i++) {
        $ACTIONS = array('IN', 'OUT', 'COMPLETE');
        $OFFICES = array('Computer Studies Department', 'CS Dean\'s Office', 'Creative Code Inc.');
        $REMARKS = array('signed', 'For approval', '');


        $date = mysqli_real_escape_string($conn, date("Y-m-d H:i:s"));
        $code = mysqli_real_escape_string($conn, $faker->numberBetween($min = 100, $max = 101));
        $action = mysqli_real_escape_string($conn, $ACTIONS[rand(0, count($ACTIONS) - 1)]);
        $office = mysqli_real_escape_string($conn, $OFFICES[rand(0, count($OFFICES) - 1)]);
        $employee = mysqli_real_escape_string($conn, $faker->name);
        $remark = mysqli_real_escape_string($conn, $REMARKS[rand(0, count($REMARKS) - 1)]);

        // echo "date: $date, code: $code, action: $action, office: $office, employee: $employee, remark: $remark";

        $query = "INSERT INTO transactions(date_log, document_code, action, office, employee, remarks) VALUES('$date', '$code', '$action', '$office', '$employee', '$remark')";

        // mysqli_query($conn, $query) or trigger_error(mysqli_error($conn). " " . $query);
        if (mysqli_query($conn, $query)) {
            // echo "$i) insert success\n\n";
        } else {
            $test = false;
            echo "$query\n";
            echo "$i) insert failed\n\n";
        }
    }

    echo $test? "insert success" : "insert failed";
}

function reset_data()
{
    require_once(dirname(__FILE__) . '\..\config\config.php');
    require_once(dirname(__FILE__) . '\..\config\db.php');

    $query = "TRUNCATE TABLE transactions";
    if (mysqli_query($conn, $query)) {
        echo "table reset successfully";
    } else {
        echo "table reset unsuccessful";
    }
}
