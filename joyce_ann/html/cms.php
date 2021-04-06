<?php
require("configuration/localhost.php");
session_start();

require("model/model.php");

$page = "page_{$_GET['page']}";
$result = function_exists($page);

# Global variable vs Local Variable

if($result)
{
    $page();
}
else
{    
    page_dashboard();
}


#------------------------------
# Helper Functions
#------------------------------

function check_session()
{
    $data = modelAccount_get($_SESSION['user']['account_id']);    
    $verifyToken = md5($data['account_email'] . $data['account_password']);  # cyclic redundancy check
    $data['success-message'] = 'hide';
    
    if($_SESSION['token'] != $verifyToken)
    {
       location('/index.php');
    }   

    if($_SESSION['success'])
    {
        # We use success session to trigger the success cover in our dashboard
        # We unset it right after so it won't appear again when you refresh the page
        $data['success-message'] = '';
        unset($_SESSION['success']);
    }   

    return $data;
}

# Function to redirect the page
function location($location)
{
    header("Location: {$location}");
    # it's a good practice to terminate the execution since we will go to another page
    # No need to run the remaining script
    die();
}

# Function to upload file.
# If you don't pass the $allowed parameter, it will use the default extension jpg and png
function upload_file($file_key, $allowed = array('jpg', 'png')) 
{
    # file key is the <input type="file" name="this is the file key" /> 
    $file_name = $_FILES[$file_key]['name'];

    # Check if a file is being uploaded.
    # If there is no file, this condition will not run.
    if($file_name) {

        # Get Extension of the file
        $ext = substr($file_name, -3);
        # Verify Extension
        $valid = in_array($ext, $allowed);
        if(!$valid)
        {
            echo "{$ext} is not allowed";
            die();
        }    
        
        # Unix Epoch Time - you can google this
        # We'll use this value together with file name and make a hash out of it
        # this is to prevent reusing of file name. 
        # because if there's an existing file with the same name, it will be overwritten
        $fileName =  md5(time() . $file_name) . "." . $ext;

        # Get data from temp file
        $data = file_get_contents($_FILES[$file_key]['tmp_name']);
        
        # Write file in the directory
        file_put_contents("view/images/$fileName", $data);
        
        # We'll return the final file name.
        return $fileName;
    }

    # If $file_name is not empty, the execution will no come at this point.
    # Because in a function, you can only return a value once and the next line will not run anymore.
    # If you look at the last part of the IF statement above, once the final file name is returned,
    # The function execution will end there. It will not get at this point.
    # it will reach here and return false if $file_name is false or empty
    return false;
}

#------------------------------
# Pages
#------------------------------

#
# Action Pages
#
function page_authentication()
{       
    $username = $_POST['username'];
    $password = $_POST['password'];    

    $login = login($username, $password);

    if($login)
    {   
        $_SESSION['user'] = $login;     
        $_SESSION['token'] = md5($username . $password);
        location('/cms.php');
    }
    else
    {        
        $_SESSION['login-error'] = true;
        header("Location: /");
    }    
}

function page_logout()
{
    session_destroy();
    header("Location: /index.php");
}

# ---------------------------------------------------
# View Pages
# ---------------------------------------------------

function page_dashboard()
{
    $account = check_session();
    $body = 'cms-dashboard.php';

    $header['title'] = 'CMS Dashboard';
    $header['name'] = $account['account_name'];
    $header['success-message'] = $account['success-message'];

    require("view/template/cms/header.php");
    require("view/{$body}");
    require("view/template/cms/footer.php");

}

function page_account_setting()
{
    # Data retrieved from account table when you log in
    $account = check_session();
    
    # Check the hidden account_id if it has a value
    # $_POST['account_id'] will contain a value only when it is submitted
    if($_POST['account_id'])
    {
        $data['account_name'] = clean($_POST['account_name']);
        $data['account_email'] = clean($_POST['account_email']);
        $data['account_password'] = clean($_POST['account_password']);
        sql_update('account', 'account_id', $account['account_id'], $data);

        # Update Token to maintain the current session if the email/password has been modified
        $_SESSION['token'] = md5($_POST['account_email']. $_POST['account_password']);
        $_SESSION['success'] = true;
        location('/cms.php?page=account_setting');
    }

    $header['title'] = 'CMS Account Setting';
    $header['name'] = $account['account_name'];
    $header['success-message'] = $account['success-message'];


    $body = 'cms-account-setting.php';    
    require("view/template/cms/header.php");
    require("view/{$body}");
    require("view/template/cms/footer.php");

}


function page_contact()
{
    $account = check_session();
    $data = modelContact_get();

    # Strip excess <br> generated by nl2br
    $data['content'] = stripBR($data['content']);

    if($_POST['update'])
    {    
        # UPDATE TABLE SET column_name = 'new value' WHERE column = 'value'    
        $update['contact_title'] = clean($_POST['contact_title']);
        $update['contact_caption'] = clean($_POST['contact_caption']);
        $update['contact_content'] = clean($_POST['contact_content']);
        # Run the update function
        sql_update('contact', 'contact_id', '1', $update);
        $_SESSION['success'] = true;
        # Relocate to the current page
        location("/cms.php?page=contact");        
    }
    
    # This part will run if $_POST['update'] is empty
    # It means this is only meant for viewing the page
    $body = 'cms-contact.php';
    $header['title'] = 'Contact Setting';
    $header['name'] = $account['account_name'];
    $header['success-message'] = $account['success-message'];

    require("view/template/cms/header.php");
    require("view/{$body}");
    require("view/template/cms/footer.php");
}

function page_about()
{
    $account = check_session();
    $data = modelAbout_get();
    # Strip excess <br> generated by nl2br
    $data['content'] = stripBR($data['content']);

    if($_POST['update'])
    {
        # Run the upload file function we created.
        # Check that function and see the explanation
        $fileName = upload_file('about_photo');   
    
        # If there's no file being uploaded, no need to update the DB record
        # As you can see, this is another way of doing conditional statement
        # This can be used if you'll just use one line of script to be executed.
        # If you need multiple lines, you need to use the usual if with { }
        if($fileName) $update['about_photo'] = $fileName;

        # UPDATE TABLE SET column_name = 'new value' WHERE column = 'value'    
        $update['about_title'] = clean($_POST['about_title']);
        $update['about_caption'] = clean($_POST['about_caption']);
        $update['about_subtitile'] = clean($_POST['about_subtitile']);
        $update['about_content'] = clean($_POST['about_content']);
        # Run the update function
        sql_update('about', 'about_id', '1', $update);
        $_SESSION['success'] = true;
        # Relocate to the current page
        location("/cms.php?page=about");        
    }
    
    # This part will run if $_POST['update'] is empty
    # It means this is only meant for viewing the page
    $body = 'cms-about.php';
    $header['title'] = 'About Page Setting';
    $header['name'] = $account['account_name'];
    $header['success-message'] = $account['success-message'];

    require("view/template/cms/header.php");
    require("view/{$body}");
    require("view/template/cms/footer.php");
}

function page_message()
{
    $account = check_session();
    $header['title'] = 'CMS Messages';
    $header['name'] = $account['account_name'];   
    $header['success-message'] = $account['success-message']; 

    # I multiplied message_id by 1 to convert it to integer
    # This is to prevent unwanted alpha values since our message id is strictly integer
    if($_GET['message_id'] * 1)
    {
        $id = $_GET['message_id'] * 1;
        $message = modelMessage_get($id);

        $body = 'cms-message-view.php';
        require("view/template/cms/header.php");
        require("view/{$body}");
        require("view/template/cms/footer.php");
        # Let's stop here.
        die();
    }
    
    # I multiplied message_id by 1 to convert it to integer
    # This is to prevent unwanted alpha values since our message id is strictly integer
    if($_GET['delete_message_id'] * 1)
    {
        $id = $_GET['delete_message_id'] * 1;
        sql_delete('list_message', 'list_message_id', $id);
        $_SESSION['success'] = true;
        location('/cms.php?page=message');
    }

    $body = 'cms-message.php';
    $data = modelMessage_getRows();

    # Get the row template
    $template = file_get_contents('view/template/cms/message-row.html');
    $rows = assembleRow($data, $template);

    require("view/template/cms/header.php");
    require("view/{$body}");
    require("view/template/cms/footer.php");
}


function page_traffic()
{
    $account = check_session();
    $header['title'] = 'CMS Traffic Monitoring';
    $header['name'] = $account['account_name'];   
    $header['success-message'] = $account['success-message']; 

    # Load Page
    $body = 'cms-traffic.php';
    $data = modelTraffic_getRows();

    # Get the row template
    $template = file_get_contents('view/template/cms/traffic-row.html');
    $rows = assembleRow($data, $template);

    require("view/template/cms/header.php");
    require("view/{$body}");
    require("view/template/cms/footer.php");
}

function assembleRow($data, $template)
{
    # Declare initial empty string
    $tempRow = "";
    $output = "";

    # Loop through the data
    foreach($data as $row)
    {
        # Renew the copy of template
        $tempRow = $template;
        foreach($row as $key => $value) {
            # Replace template keys with values
            $key = "--{$key}--";
            $tempRow = str_replace($key, $value, $tempRow);
        }
        # Contact the populated template
       $output = $output . $tempRow;
    }
    return $output;
}