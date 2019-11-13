<?php
$this->options = get_option( 'appmaker_wc_settings' );
if ( empty( $this->options['project_id'] ) ) {
    ?>
    <div class="main-box settings">
        <div class="box-header">
            <h3>What's next?</h3>
        </div>
        <div class="box-body">
            <p>Kindly <u><b><a href="https://appmaker.xyz/book-a-demo/?utm_source=woocommerce-plugin&utm_medium=what-next&utm_campaign=after-plugin-install" target="_blank">book demo</a></b></u> to receive <i>Project ID, API Key and API Secret</i>. Make sure to be specific with details on the demo page.</p>
        </div>
        <div class="row infograph-container">
            <div class="column main infograph">
                <h5>1</h5>
                <img src="https://fb7561574c.to.intercept.rest/7b2b40fc-call-us.png" alt="">
                <h3>Talk to us</h3>
                <p>Book call with our app experts to let us know about requirements and functionalities for app.</p>
            </div>
            <div class="column main infograph">
                <h5>2</h5>
                <img src="https://fb7561574c.to.intercept.rest/d3a30739-buils-app.png" alt="">
                <h3>Build your app</h3>
                <p>Either DIY (Do it yourself) or our dedicated App team build the app for you. Just need to share images and details.</p>
            </div>
            <div class="column main infograph">
                <h5>3</h5>
                <img src="https://fb7561574c.to.intercept.rest/4f330121-upload.png" alt="">
                <h3>Publish & Promote</h3>
                <p>We help you publish your E-Commerce app to Playstore and Appstore. Quality and Performance assured</p>
            </div>
        </div>
    </div>
<?php } ?>
    <div class="main-box api-detail">
        <div class="box-body">
            <form method="post" action="">
                <?php
                // This prints out all hidden setting fields.
                settings_fields( 'appmaker_wc_key_options' );
                do_settings_sections( 'appmaker-wc-setting-admin' );
                submit_button($name='Activate');
                ?>
            </form>
        </div>
    </div>
