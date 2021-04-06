<?php
$GLOBALS['sql-connection'] = sql_connect();

function sql_connect(){  
    $sql_host = CONFIG_DB_HOST;
    $sql_user = CONFIG_DB_USER;
    $sql_pass = CONFIG_DB_PASS;
    $sql_db = CONFIG_DB_DBNAME;
    $conn = mysqli_connect($sql_host, $sql_user, $sql_pass, $sql_db);
    $conn_error = mysqli_connect_error();    
    if($conn_error){
        echo "Unable to establish database connection";
        die();
    }
    else {
        return $conn;
    }
}

function sql_select($query){    
    $result = mysqli_query($GLOBALS['sql-connection'], $query);
    if(!$result){
        echo mysqli_error($GLOBALS['sql-connection']);
        die();
    }
    $fetch = mysqli_fetch_all($result, MYSQLI_ASSOC);
    return $fetch;
}

function sql_insert($data, $table){
    foreach($data as $key => $value){
        $keys[] = $key;
        $values[] = "'{$value}'";
    }
    $column = implode(',', $keys);
    $value = implode(',', $values);
    $query = "INSERT INTO {$table} ({$column}) VALUES ({$value})";

    $result =mysqli_query($GLOBALS['sql-connection'], $query);
    if(!$result){
        echo mysqli_error($GLOBALS['sql-connection']);
        die();
    }    
}

function sql_update($tableName, $refColumn, $refValue, $data)
{
    
    foreach($data as $key => $value)
    {
        $sets[] = "{$key} = '{$value}'";
    }
    $sets = implode(',', $sets);

    $query = "UPDATE {$tableName} SET {$sets} WHERE {$refColumn} = '{$refValue}'";
    
    $result = mysqli_query($GLOBALS['sql-connection'], $query);
    if(!$result)
    {
        echo mysqli_error($GLOBALS['sql-connection']);
        pr($query);
        die();
    }    
}

function sql_delete($table, $column_id, $column_value)
{
    $query = "DELETE FROM {$table} WHERE {$column_id} = '{$column_value}'";
    $result = mysqli_query($GLOBALS['sql-connection'], $query);
    if(!$result)
    {
        echo mysqli_error($GLOBALS['sql-connection']);
        pr($query);
        die();
    } 
}

#------------------------------
# Helper Functions
#------------------------------

function pr($array)
{
    echo '<pre>';
    print_r($array);
    echo '</pre>';
}

function login($email, $password)
{
    # Unsercure
    $query = "select * from account where account_email = '{$email}' 
    AND account_password = '{$password}' " ;   
    
    # Secured
    
    $row = sql_select($query);
    return $row[0];
}

# Clean and santize input
# We only allow the tags listed in default
# rest, like <script> <style> and others will be stripped off
function clean($string, $allowedTags = '<a><div><p><br>')
{
    # Escape ' and " so it won't be interpreted as SQL command
    # Back slash is used to escape these characters    
    $string = addslashes($string);
    $string = strip_tags($string, $allowedTags);
    
    # new line to break converts new line into <br>
    # this is used for multi line texts like in your contact us page.
    $string = nl2br($string);

    return $string;
}

# Use strip BR to remove <br> in textarea generated by nl2br
# Used only for output in textarea
function stripBR($string)
{
    $string = strip_tags($string, '<a><div><p>');
    return $string;
}


#------------------------------
# Data Models
#------------------------------

function modelHome_get()
{
    $home = sql_select("select * from home where home_id = 1");
    $home = $home[0];
    $data['banner'] = $home['home_banner'];
    $data['title'] =  $home['home_title'];
    $data['caption'] = $home['home_caption'];
    $data['subtitle'] = $home['home_subtitle'];
    $data['subtitle-caption'] = $home['home_subtitle_caption'];
    return $data;
}

function modelAbout_get(){
    $about = sql_select("select * from about");
    $about = $about[0];
    $data['title'] = $about['about_title'];
    $data['caption'] = $about['about_caption'];
    $data['subtitle'] = $about['about_subtitile'];
    $data['content'] = $about['about_content'];
    $data['photo'] = $about['about_photo'];
    return $data;
}

function modelContact_get(){    
    $contact = sql_select("select * from contact");
    $contact = $contact[0];    
    $data['title'] = $contact['contact_title'];
    $data['caption'] = $contact['contact_caption'];
    $data['content'] = $contact['contact_content'];
    return $data;
}

function modelAccount_get($account_id){    
    $data = sql_select("select * from account where account_id = '{$account_id}' ");
    return $data[0];
}

function modelMessage_getRows()
{
    # We ordered by descending so that the newest message is on top

    $data = sql_select("select * from list_message ORDER BY list_message_id DESC");
    
    return $data;
}

function modelTraffic_getRows()
{
    # We ordered by descending so that the newest message is on top
    $data = sql_select("select * from list_traffic ORDER BY list_traffic_id DESC");
    
    # Declare an emtpy array (initialize)
    $rows = array();
    # Convert timestamp to readable time
    foreach($data as $value)
    {
        # For date, refer to https://www.php.net/manual/en/function.date.php
        $value['list_traffic_time'] = date("F j, Y, g:i a", $value['list_traffic_time']);
        $rows[] = $value;
    }
    return $rows;
}

function modelMessage_get($id)
{
    $data = sql_select("select * from list_message where list_message_id = '{$id}'");    
    return $data[0];
}

function modelInsertTracking($data)
{
    $insert['list_traffic_ip'] = $data['list_traffic_ip']; 
    $insert['list_traffic_agent'] = $data['list_traffic_agent']; 
    $insert['list_traffic_url'] = $data['list_traffic_url']; 
    $insert['list_traffic_time'] = $data['list_traffic_time']; 
    sql_insert($insert, 'list_traffic');
}