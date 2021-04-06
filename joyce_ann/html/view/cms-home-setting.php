<div id="dash-title" class="row">
    <div class="row-mid">
        <h1>Home Settings</h1>
    </div>
</div>

<div class="row">
    <div class="row-mid">
        <form id="cms-form" action="cms.php?page=home_setting" method="post" enctype="multipart/form-data">
        <input type="hidden" value="true" name="update" />
        <table style="width: 100%">
            <tr>
                <td style="width: 15%">Banner</td>
                <td style="width: 85%">
                    <div class="input">
                        <input name="home_banner" type="file">
                    </div>
                </td>
            </tr>
            <tr>
                <td>Title</td>
                <td>
                    <div class="input">
                        <input name="home_title" type="text" value="<?php echo $data['title']; ?>" >
                    </div>
                </td>
            </tr>  
            <tr>
                <td>Title Caption</td>
                <td>
                    <div class="input">
                        <input name="home_caption" type="text" value="<?php echo $data['caption']; ?>">
                    </div>
                </td>
            </tr>                    
            <tr>
                <td>Subtitle</td>
                <td>
                    <div class="input">
                        <input name="home_subtitle" type="text" value="<?php echo $data['subtitle']; ?>">
                    </div>
                </td>
            </tr>
            <tr>
                <td>Subtitle Caption</td>
                <td>
                    <div class="input">
                        <input name="home_subtitle_caption" type="text" value="<?php echo $data['subtitle-caption']; ?>">
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