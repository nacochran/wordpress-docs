/** Add FontAwesome Icons **/
function enqueue_fa_script() {
	wp_enqueue_script('fascript', 'https://kit.fontawesome.com/c828ed0691.js');
}
add_action('wp_enqueue_scripts', 'enqueue_fa_script');

/** WordPress custom function **/
function my_custom_function(){
    ?>
    <script>
        // Your function here
        jQuery(window).load(function() {
            console.log('Hello World!');
        });
    </script>
    <?php
}
// Add custom function at location, such as wp_head
add_action('wp_head', 'my_custom_function');
