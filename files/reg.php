<?php

    $servername ='localhost';
    $db_user_name ='root';
    $db_user_password ='';
    $dbname ='students_result';

    $con = mysqli_connect($servername, $db_user_name, $db_user_password, $dbname); # Connects db.

    if(mysqli_connect_error()){ # check if not connected
        exit('Error connecting to the database'.mysqli_connect_error());
    }

    if(!isset($_POST['surname'], $_POST['firstname'], $_POST['matric'], $_POST['password'])){ # Check if the any field is not clicked.
        exit('Field(s) cannot be empty');
    }

    if(empty($_POST['surname'] || empty($_POST['firstname']) || empty($_POST['matric']) || empty($_POST['password']))){
        exit('Values cannot be Empty!'); # Check if any field value is empty.
    }

    if($stmt = $con->prepare('SELECT id, Firstname FROM users WHERE MatricNumber=?')){ # To query db for existing row values in the MatricNumber column.

        $stmt->bind_param('s', $_POST['matric']); # To bind the matric number.
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows()>0){ # Check if input is already in the db.
            echo 'Matriculation  number already exists. Login to view result';
        }

        else{
            if($stmt = $con->prepare("INSERT INTO users (surname, Firstname, MatricNumber, Password) VALUES (?, ?, ?, ?)")){ # uploads the students details into the db
                
                $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
                $surname =$_POST['surname'];
                $firstname =$_POST['firstname'];
                $matric= $_POST['matric'];

                $stmt->bind_param('ssss', $surname, $firstname, $matric, $password);
                $stmt->execute();
                echo 'Registration Successful';
            }

            else{
                echo 'Registration Failed';
            }

        
        } 
        $stmt->close();  # This is where  I have the error but I don't know how to fix it. If this line works, the data will be uploaded into the db'

    }
    else{
        echo 'Error occured';
    }
    

    $con->close(); 

?>