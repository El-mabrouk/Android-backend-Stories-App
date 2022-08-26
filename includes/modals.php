<div class="modal fade" id="modal-package-name" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">What is my package name?</h4>
            </div>
            <div class="modal-body">
                <li>Open Android Studio Project</li>
                <li>Select <b></> Gradle Scripts > build.gradle (Module: app)</b><img src="assets/images/package_name.jpg" class="img-responsive"></li>                            
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK, I AM UNDERSTAND</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-server-key" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Obtaining your Firebase Server API Key</h4>
            </div>
            <div class="modal-body">
                <p>Firebase provides Server API Key to identify your firebase app. To obtain your Server API Key, go to firebase console, select the project and go to settings, select Cloud Messaging tab and copy your Server key.</p>
                <img src="assets/images/fcm-server-key.jpg" class="img-responsive">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK, I AM UNDERSTAND</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-api-key" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Where I have to put my API Key?</h4>
            </div>
            <div class="modal-body">
                <ol>
                    <li>for security needed, Update <b>API_KEY</b> String value.</li>
                    <li>Open Android Studio Project.</li>
                    <li>Click <b>CHANGE API KEY</b> to generate new API Key.</li>
                    <li>go to app > java > yourpackage name > config > <b>AppConfig.java</b>, and update with your own Server Key and Rest API Key. <img src="assets/images/api_key.jpg" class="img-responsive"></li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK, I AM UNDERSTAND</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-youtube-api-key" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Get YouTube API Key</h4>
            </div>
            <div class="modal-body">
             <ol>
                <li>Go to <a href="https://console.developers.google.com/" target="_blank"><b>Google Developers Console</b></a> and log in with your Google account.</li>
                <li>Create the new project (name does not matter for the plugin).</li>
                <li>Go to your project (by clicking on its name in the list).</li>
                <li>In the sidebar on the left, expand APIs & Services → Library. In the list of APIs go to <b>YouTube Data API (v3)</b> and make sure that it’s <b>enabled (Enable API)</b>.</li>
                <li>In the same sidebar click Credentials. Then click on Create New Key and choose API key.</li>
                <li>Copy API key and paste it in the admin panel youtube api key settings form.</li>
                <li>For video tutorial, klik this link <a href="https://youtu.be/eAkyaEHMJUE" target="_blank"><b>HERE</b></a></li>
            </ol>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK, I AM UNDERSTAND</button>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="modal-onesignal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Where do I get my OneSignal APP ID and Rest API Key?</h4>
            </div>
            <div class="modal-body">
                <ol>
                    <li>Login to your <a href="https://onesignal.com/" target="_blank"><b>OneSignal</b></a> Account and select your app</li>
                    <li>Select <b></> SETTINGS > Keys & IDs.</b> <img src="assets/images/onesignal_key.jpg" class="img-responsive"></li>
                </ol>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK, I AM UNDERSTAND</button>
            </div>
        </div>
    </div>
</div>  

<div class="modal fade" id="modal-admob-app-id" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Update your AdMob App ID</h4>
            </div>
            <div class="modal-body">
                <p>Put your <b>AdMob App ID</b> to <b>res/value/ads.xml</b> in the <b>admob_app_id</b> string tag.</p>
                <img src="assets/images/admob_app_id.jpg" class="img-responsive">
                <p><b>Important:</b> This step is required! Failure to add your app id in the string tag results in a crash with the message: The Google Mobile Ads SDK was initialized incorrectly.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK, I AM UNDERSTAND</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modal-applovin-sdk-key" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="largeModalLabel">Update your AppLovin SDK Key</h4>
            </div>
            <div class="modal-body">
                <p>Put your <b>AppLovin SDK Key</b> to <b>res/value/ads.xml</b> in the <b>applovin_sdk_key</b> string tag.</p>
                <img src="assets/images/admob_app_id.jpg" class="img-responsive">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link waves-effect" data-dismiss="modal">OK, I AM UNDERSTAND</button>
            </div>
        </div>
    </div>
</div>