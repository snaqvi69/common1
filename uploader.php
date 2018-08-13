<?php 


    if ($_FILES['loadedFile']["error"] > 0)

        echo "Error: ". $_FILES["loadedFile"] ["error"]. "</br>";


    else
    {
        echo "Upload: ". $_FILES["loadedFile"] ["name"]. "</br>";
        echo "Type: ". $_FILES["loadedFile"] ["type"] . "</br>";
        echo "Stored in: " .$_FILES["loadedFile"] ["tmp_name"];
        echo "Size: ". ($_FILES["loadedFile"] ["size"] / 1024). " Kb</br>";

        //Copies file from TEMP_PHP dir to d.default dir
        if (file_exists("." . $_FILES["loadedFile"]["name"]))
        {
            echo $_FILES["loadedFile"]["name"] . " already exists. ";
        }

        else
        {
            move_uploaded_file($_FILES["loadedFile"]["tmp_name"],"../upload/" . $_FILES["loadedFile"]["name"]);
            echo "Stored in: " . "../upload/" . $_FILES["loadedFile"]["name"];
        }
    }
     
 ?> 