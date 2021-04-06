<div id="dash-title" class="row">
    <div class="row-mid">
        <h1>Account Settings</h1>
    </div>
</div>

<div class="row">
    <div class="row-mid">
    <form id="cms-form" action="cms.php?page=account_setting" method="post" enctype="multipart/form-data">
        <input type="hidden" name="account_id" value="<?php echo $account['account_id']; ?>" />
        <table style="width: 100%">
            <tr>
                <td style="width: 15%">Full Name</td>
                <td style="width: 85%">
                    <div class="input">
                        <input name="account_name" type="text" value="<?php echo $account['account_name']; ?>" />
                    </div>
                </td>
            </tr>
            
            <tr>
                <td>Email</td>
                <td>
                    <div class="input">
                        <input name="account_email" type="text" value="<?php echo $account['account_email']; ?>" />
                    </div>
                </td>
            </tr>            
            <tr>
                <td>CMS Password</td>
                <td>
                    <div class="input">
                        <input name="account_password" type="password" value="<?php echo $account['account_password']; ?>">
                    </div>
                </td>
            </tr>
            <tr>
                <td colspan=2 style="text-align: right">
                    <a href="cms.php" class="button" style="background-color: crimson;">Close</a>
                    <a class="button" onclick="$('#cms-form').submit()">Save</a>
                </td>
            </tr>
        </table>
    </form>
    </div>
</div>