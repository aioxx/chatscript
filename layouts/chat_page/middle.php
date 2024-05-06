<div class="col-md-7 col-lg-9 middle page_column" column="second">

    <div class="video_preview d-none">
        <span class="icons">
            <span class="close_player">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
                </svg>
            </span>
        </span>
        <div>
        </div>
    </div>

    <div class="iframe_window d-none">
        <span class="icons">
            <span class="close_iframe_window">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
                </svg>
            </span>
        </span>
        <div>
        </div>
    </div>

    <div class="group_headers d-none">
        <span class="icons">
            <span class="close_group_header">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2H2zm3.354 4.646L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 1 1 .708-.708z" />
                </svg>
            </span>
        </span>
        <div class="header_content"></div>
    </div>

    <div class="confirm_box d-none animate__animated animate__flipInX">
        <div class="error">
            <span class="message"><?php echo(Registry::load('strings')->error) ?> : <span></span></span>
        </div>
        <div class="content">
            <span class="text"></span>
            <span class="btn cancel" column="second"><span></span></span>
            <span class="btn submit"><span></span></span>
        </div>
    </div>

    <div class="content">

        <div class="welcome_screen">
            <?php include 'layouts/chat_page/welcome_screen.php'; ?>
        </div>

        <div class="statistics d-none">
            <?php include 'layouts/chat_page/statistics.php'; ?>
        </div>

        <div class="custom_page d-none">
            <?php include 'layouts/chat_page/custom_page.php'; ?>
        </div>

        <div class="chatbox d-none boundary">
            <?php include 'layouts/chat_page/chatbox.php'; ?>
        </div>
    </div>


</div>