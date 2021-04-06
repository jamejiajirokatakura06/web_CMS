<!DOCTYPE html>
<html>
    <head>
        <title><?php echo $header['title']; ?></title>
        <link rel="stylesheet" href="view/css/global.css" />
        <link rel="stylesheet" href="view/css/cms.css" />
    </head>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.js" integrity="sha256-DrT5NfxfbHvMHux31Lkhxg42LY6of8TaYyK50jnxRnM=" crossorigin="anonymous"></script>
    <body>
    <script>
        

        function removeSuccess()
        {
            $('#succss-message').addClass('hide');
        }

        
    </script>
        <div id="succss-message" class="cover <?php echo $header['success-message']; ?>">
            <div class="background"></div>
            <div class="form">            
                <div class="row-mid">
                    <div class="center" style="color: rgb(40, 128, 22);">
                        Success!!
                        <br/><br/>
                    </div>
                    <form id="login-form" action="cms.php?page=authentication" method="POST">
                        <table style="width:100%">                    
                            <tr>
                                <td colspan="2" style="text-align: center;">
                                    <a onclick="removeSuccess()" class="button medium" style="background-color: crimson;">Close</a>
                                    
                                </td>
                            </tr>
                        </table>
                    </form>
                </div>                
            </div>        
        </div>
        <div id="dash-header" class="row">
            <div class="row-mid">
                <div class="left">Welcome, <?php echo $header['name']; ?></div>
                <div class="right">
                    <a href="cms.php" class="button" style="background-color: rgb(131, 211, 52);">Home</a>
                    <a href="cms.php?page=logout" class="button">Logout</a>
                </div>
            </div>
        </div>