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
        $name = mysqli_real_escape_string($conn, $faker->name);
        $contact = mysqli_real_escape_string($conn, $faker->phoneNumber);
        $email = mysqli_real_escape_string($conn, $faker->email);
        $address = mysqli_real_escape_string($conn, $faker->address);
        $city = mysqli_real_escape_string($conn, $faker->city);
        $country = mysqli_real_escape_string($conn, $faker->country);
        $postal = mysqli_real_escape_string($conn, $faker->postcode);

        // echo "name: $name, contact: $contact, email: $email, address: $address, city: $city, country: $country, postal: $postal";

        $query = "INSERT INTO offices(name, contact_no, email, address, city, country, postal) VALUES('$name', '$contact', '$email', '$address', '$city', '$country', '$postal')";

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

    $query = "TRUNCATE TABLE offices;";
    if (mysqli_query($conn, $query)) {
        echo "table reset successfully";
    } else {
        echo "table reset unsuccessful";
    }
}
