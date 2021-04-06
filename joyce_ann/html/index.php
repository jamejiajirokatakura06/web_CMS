<?php
require("configuration/localhost.php");
require("model/model.php");
session_start();

# Page Tracking here.
# Define data values
$insertTracking['list_traffic_ip'] = clean($_SERVER['REMOTE_ADDR']);
$insertTracking['list_traffic_agent'] = clean($_SERVER['HTTP_USER_AGENT']); 
$insertTracking['list_traffic_url'] = clean($_SERVER['REQUEST_URI']); 
$insertTracking['list_traffic_time'] = time();

# Insert
modelInsertTracking($insertTracking);


# Query String
#echo $_GET['page'];
#echo $_GET['data'];
$page = "page_{$_GET['page']}";

$result = function_exists($page);

if($result)
{
    $page();
}
else
{
    page_about();
}

#------------------------------------------------------
# Helper Functions
#------------------------------------------------------

function pageLoader($body, $values = array())
{
    if($_SESSION['login-error'])
    {
        $login = '';        
    } 
    else
    {
        $login = 'hide';
    }
    
    $header = file_get_contents("view/template/header.html");
    $header = str_replace("--css-page--", $body['css-page'], $header);
    $header = str_replace("--login--", $login, $header);    
    $body = file_get_contents("view/{$body['body']}");
    $footer = file_get_contents("view/template/footer.html");
    $output = $header . $body . $footer;
    
    foreach($values as $x => $value)
    {
        $key = "--{$x}--";
        $output = str_replace($key, $value, $output);
    }
    # Unset deletes a row in an array
    unset($_SESSION['login-error']);
    return $output;    
}

#------------------------------------------------------
# Public Pages
#------------------------------------------------------

# I think home page is redundant. As a personal page, About Me page is enough for us

// function page_home()
// {
//     $body['css-page'] = "home.css";
//     $body['body'] = 'home.html';

//     $home = modelHome_get();
//     $thisPage = pageLoader($body, $home);
//     echo $thisPage;    
// }

function page_about()
{    
    $body['css-page'] = "about.css";
    $body['body'] = 'about.html';
    

    $about = modelAbout_get();
    $values['title'] = $about['title'];
    $values['caption'] = $about['caption'];
    $values['subtitle'] = $about['subtitle'];
    $values['content'] = $about['content'];
    $values['about-image'] = $about['photo'];       

    $thisPage = pageLoader($body, $values);
    echo $thisPage;  
}



function page_contact()
{
    if($_POST['send'])
    {
        $data['list_message_name'] = clean($_POST['list_message_name']);
        $data['list_message_surname'] = clean($_POST['list_message_surname']);
        $data['list_message_company'] = clean($_POST['list_message_company']);
        $data['list_message_email'] = clean($_POST['list_message_email']);
        $data['list_message_mobile'] = clean($_POST['list_message_mobile']);
        $data['list_message_subject'] = clean($_POST['list_message_subject']);
        $data['list_message_content'] = clean($_POST['list_message_content']);
        sql_insert($data, 'list_message');
        header("Location: /?page=contact&success=true");
        die();
    }    

    $body['css-page'] = "contact.css";
    $body['body'] = 'contact.html';
    
    $contact = modelContact_get();
    if($_GET['success'])
    {                 
        $contact['contact-sent'] = '';
    }
    else
    {
        $contact['contact-sent'] = 'hide';
    }

    $thisPage = pageLoader($body, $contact);
    echo $thisPage;
}