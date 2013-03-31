<?php
    function getStatData($table){
        global $con;
        $query = "select * from $table";
        $result = mysql_query($query,$con);
                //var_dump($result);exit;
        $data = array();
        while($row = mysql_fetch_array($result)){
            $current_imps = array("","","","","","","","","","","","","","");
            $impressions = $row['impressions'];
            $imp_array = explode(",",$impressions);
            if(count($imp_array)<14){
                $imp_array = array_reverse($imp_array);
                for($i=0;$i<count($imp_array);$i++){
                     $current_imps[13-$i] = $imp_array[$i];
                }
               
            }else{
                $imp_array = array_reverse($imp_array);
                for($i=0;$i<14;$i++){
                     $current_imps[13-$i] = $imp_array[$i];
                }
            }
            $data[$row['keyword']] = $current_imps;
        }//End while
        return $data;
    }//End getStatData

?>
