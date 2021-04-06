<div id="dash-title" class="row">
    <div class="row-mid">
        <h1>Messages</h1>
    </div>
</div>

<div class="row">
    <div class="row-mid">
        <table style="width: 100%" class="table-list">
            <tr class="header">
                <!-- 
                    this 
                    <?=$value?>

                    is shorcut for 
                    <?php echo $value ?>
                -->
                <td style="width: 35%">From: <?=$message['list_message_name']?> <?=$message['list_message_surname']?></td>
                <td style="width: 35%">Subject: <?=$message['list_message_subject']?></td>  
                <td style="width: 30%">Company: <?=$message['list_message_company']?></td>                 
            </tr>  
            <tr class="header">                
                <td>Email: <?=$message['list_message_email']?></td>
                <td>Mobile: <?=$message['list_message_mobile']?></td>  
                <td><a class="button" href="cms.php?page=message"> << Back</a></td>                 
            </tr>  
            <tr class="list">
                <td colspan="3">
                    <?=$message['list_message_content']?>
                </td>
            </tr>
        </table>
    </div>
</div>

