    <form action="" method="post"
        enctype="multipart/form-data">
        <label for="file">Filename:</label>
        <input type="file" name="file" id="file"><br>
        <input type="submit" name="submit" value="Submit">
    </form>

<?php 
    require_once 'parsecsv.lib.php';
    if(isset($_FILES["file"])){
    if(isset($_FILES["file"])){
        if ($_FILES["file"]["error"] > 0){
            header('Location: upload.php');
            echo "Error: " . $_FILES["file"]["error"] . "<br>";
        }else{
            echo "Upload: " . $_FILES["file"]["name"] . "<br>";
            echo "Type: " . $_FILES["file"]["type"] . "<br>";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
            echo "Stored in: " . $_FILES["file"]["tmp_name"];
        }
    }
    if(file_exists("uploads/" . $_FILES["file"]["name"])){
        echo $_FILES["file"]["name"] . " already exists. ";
    }else{
        move_uploaded_file($_FILES["file"]["tmp_name"],
        "uploads/".$_FILES["file"]["name"]);
        echo "Stored in: "."uploads/".$_FILES["file"]["name"];
        $file_name = "uploads/".$_FILES["file"]["name"];
    }
      
    //Parsing uploaded file  
    function checkKeywordExists($keyword){
        if($keyword == ""){
            return true;
        }
        $query = "select * from products where keyword='{$keyword}'";
        global $con;
        mysql_query($query,$con);
        if(mysql_affected_rows()>0){
           return true;
        }else{
           return false; 
        }        
    }
    $con = mysql_connect("localhost","root","");
    if(!$con){
        die("Connection failed! ".mysql_error());
    }
    $select_db = mysql_select_db("stats",$con);
    if(!$select_db){
        die("Data base is not selected! ".mysql_error());
    }
    
    $csv = new parseCSV();
    $csv->encoding('UTF-16', 'UTF-8');
    $csv->delimiter = "\t";
    $csv->parse($file_name);
    
    foreach ($csv->data as $k=>$v){
        
        $keyword = $v["Keyword"];
        $dates = date("Y/m/d");
        $difficulty = $v["Average Position"];
        $cpc = $v["Average CPC"];
        $ctr = $v["CTR"];
        $impressions = $v["Impressions"];
       
        
        if(checkKeywordExists($keyword) == false){
            $query = "insert into products (keyword,dates,difficulty,cpc,ctr,impressions) values ('{$keyword}', '{$dates}','{$difficulty}','{$cpc}', '{$ctr}','{$impressions}')";
            mysql_query($query,$con);
            if(mysql_affected_rows()>0){
               
            }
        }else{
            $query = "select * from products where keyword='{$keyword}' ";
            $result = mysql_query($query,$con);
            $row = mysql_fetch_assoc($result);
            $dates = $row["dates"].",".$dates;
            $difficulty = $row["difficulty"].",".$difficulty;
            $cpc = $row["cpc"].",".$cpc;
            $ctr = $row["ctr"].",".$ctr;
            $impressions = $row["impressions"].",".$impressions;

            $query = "update products set dates='{$dates}',difficulty='{$difficulty}',cpc='{$cpc}',ctr='{$ctr}',impressions='{$impressions}' where keyword='{$keyword}'";
            mysql_query($query);
        }
        
    }//End of foreach
    unlink("uploads/".$_FILES["file"]["name"]);
    
    }
?>


