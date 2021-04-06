<?php

$name = "-first-name- -last-name-";

$name = str_replace("-first-name-", "Arnie", $name);

$aboutHTML = file_get_contents("view/about.html");
$aboutContent = file_get_contents("model/about-body.txt");
$output = str_replace("--content--", $aboutContent, $aboutHTML );

$globalCSS = "view/css/global.css";
$aboutCSS = "view/css/about.css";

$output = str_replace("--css-global--", $globalCSS, $output );
$output = str_replace("--css-about--", $aboutCSS, $output );

# Associative Array
$myArray['name'] = "Arnie";
$myArray['surname'] = "Alejandro";
$myArray['gender'] = "Male";


$view['content'] = file_get_contents("model/about-body.txt");
$view['css-global'] = "view/css/global.css";
$view['css-about'] = "view/css/about.css";

$salutation = "Mr";
$name = "{$salutation} Arnie Alejandro";

$aboutHTML = file_get_contents("view/about.html");
# Foreach Loop
foreach($view as $x => $value)
{    
    $key =  "--{$x}--";
    $output = str_replace($key, $value, $aboutHTML);
    $aboutHTML = $output;
}

echo $aboutHTML;


# PHP Function
function pr($myArray)
{
    echo '<pre>';
    # Print array content
    print_r($myArray);
    echo '</pre>';
}




