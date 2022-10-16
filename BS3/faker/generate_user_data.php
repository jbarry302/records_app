<?php

generate_data();
// reset_data();


function generate_data() {
    require('vendor/autoload.php');
    require_once(dirname(__FILE__) . '\..\config\config.php');
    require_once(dirname(__FILE__) . '\..\config\db.php');
    
    
    $faker = Faker\Factory::create();
    
    $test = true;
    
    for ($i = 0; $i < 200; $i++) {
        $lastname = mysqli_real_escape_string($conn, $faker->lastName);
        $firstname = mysqli_real_escape_string($conn, $faker->firstName);
        $address = mysqli_real_escape_string($conn, $faker->address);
        $office = mysqli_real_escape_string($conn, $faker->company);
    
        // echo "name: $name, contact: $contact, email: $email, address: $address, city: $city, country: $country, postal: $postal";
    
        $query = "INSERT INTO employees(last_name, first_name, address, office) VALUES('$lastname', '$firstname', '$address', '$office')";
    
        if (mysqli_query($conn, $query)) {
            // echo "$i) insert success\n\n";
        } else {
            $test = false;
            echo "$query\n";
            echo "$i) insert failed\n\n";
        }
    }
    
    echo $test ? "insert success" : "insert failed";
}

function reset_data()
{
    require_once(dirname(__FILE__) . '\..\config\config.php');
    require_once(dirname(__FILE__) . '\..\config\db.php');

    $query = "TRUNCATE TABLE employees;";
    if (mysqli_query($conn, $query)) {
        echo "table reset successfully";
    } else {
        echo "table reset unsuccessful";
    }
}

?>
