<?php

/**
 * File : general.php
 * Author: Nathan Cochran
 * Use Freely
**/


/*****************************************
* Add ACF Options Page
******************************************/
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}


/*****************************************
 * Custom Post Types (CPTs)
******************************************/
function create_post_type() {
	register_post_type( 'team', // CPT code name
		array(
			'labels' => array(
				'name' => __( 'Team' ), // CPT group name
				'singular_name' => __( 'Team' ) // CPT singular name
			),
		'public' => true,
		'has_archive' => false,
		'supports' => array( 'title', 'editor', 'thumbnail', 'page-attributes'),
		'menu_position' => 5,
		'hierarchical' => true,
		)
	);

  	// create a category attribute for the team category
	register_taxonomy(
		'team-cat',
		'team',
		array(
			'label' => __( 'Team Category' ),
			'hierarchical' => true,
			'public' => true,
			'publicly_queryable' => false,
			'show_admin_column' => true
		)
	);
}
add_action( 'init', 'create_post_type' );

/*****************************************
 * Using WP_Query with CPTs
******************************************/
/**
 * @function teamShortcode
 * Displays Staff members (Custom Post Type)
 * [team type="team" category="regional representatives"]
**/
function teamShortcode( $atts ) {
	ob_start(); // create a "cache" of PHP code
	
	$options = shortcode_atts( array (
		'post_type' => '#',
		'category_type' => '#',
		'link' => 'yes'
	), $atts);
?>

<!--------------------------------------------------------
-- Display all staff
---------------------------------------------------------->
	<div class="team">
<?php
	$args = array(
		'post_type' => $options['post_type'],
		'taxonomy' => 'team-cat',
		'term' => $options['category_type'],
		'posts_per_page' => -1,
		'orderby' => 'date',
		'order' => 'ASC',
		'post__not_in' => array($exclude)
	);
	
	// The Query
	$the_query = new WP_Query( $args );

	// The Loop
	if ( $the_query->have_posts() ) {
		$post_number = 0;
		while ( $the_query->have_posts() ) {
			$the_query->the_post();
			
			$class='';
			if($post_number == 0)
			{
				$class = ' first';
			}
			
			$link = ($options['link'] == 'yes') ? get_permalink() : "";
			
			
			echo '<div class="column'.$class.'"><a href="'.$link.'">';
			echo '<div class="teamimage">';
			if ( has_post_thumbnail() ) {
				the_post_thumbnail();
			}
			else {
				echo '<img src="https://learningforwardmo.org/wp-content/uploads/2023/05/Photo-Coming-Soon.jpg" alt="No Photo" title="No Photo" />';
			}
			echo '</div><div class="teamname">'.get_the_title().'</div>';
			
			echo '</a></div>';
			
			$post_number++;
			if($post_number == 5)
			{
				$post_number = 0;
			}
		}
	}
	
	/* Restore original Post Data */
	wp_reset_postdata();
?>
		</div>
<?php
	return ob_get_clean();
}

// add shortcode 'caregivers' for function
add_shortcode( 'team', 'teamShortcode' );


/*****************************************
* Year shortcode
******************************************/
function yearshortcode( $atts ) {
	return date('Y');
}
add_shortcode( 'year', 'yearshortcode' );

/*****************************************
* FontAwesome Icons
******************************************/
function enqueue_fa_script() {
	wp_enqueue_script('fascript', 'https://kit.fontawesome.com/c828ed0691.js');
}
add_action('wp_enqueue_scripts', 'enqueue_fa_script');'


/*****************************************
 * Define a custom function
 * Add an action to call it at a specific head
******************************************/
function my_custom_function() {
    ?>
    <script>
        // Your function here
        jQuery(window).load(function() {
            console.log('Hello World!');
        });
    </script>
    <?php
}
add_action('wp_head', 'my_custom_function');



