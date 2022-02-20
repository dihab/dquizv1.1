<?php
/*Plugin Name: D-QUIZ
Plugin URI: http://wordpress.org/plugins/
Description: Create Quiz and share it with your audiance
Author: MD. Efthakhar Dihab
Version: 1.0.2
Author URI:
Text Domain: dquiz
*/
require_once( ABSPATH . '/wp-admin/includes/upgrade.php' );


//// dequiz activation hook
function dquiz_activate() {
    global $wpdb;

    // dquiz question table creation on activation
    $table_name_dquiz_questions = $wpdb->prefix . 'dquiz_questions';
    $sql_to_qs_table = "CREATE TABLE {$table_name_dquiz_questions} (
        quiz_Set_Id varchar(20),
        quiz_Id varchar(20) NOT NULL,
        quiz_Qs varchar(255),
        ans_type varchar(25),
        PRIMARY  KEY  (quiz_Id)
        )";
    dbDelta($sql_to_qs_table);


     // dquiz Ans table creation on activation
    $table_name_dquiz_ans = $wpdb->prefix . 'dquiz_ans';
    $sql_to_ans_table = "CREATE TABLE {$table_name_dquiz_ans} (
        quiz_Set_Id varchar(20),
        related_quiz_Id varchar(20),
        ans_id varchar(20),
        ans varchar(255),
        ans_selected int(1),
        ans_point int,
        PRIMARY  KEY  (ans_id)
        )";
    dbDelta($sql_to_ans_table);


    // dquiz quizset meta info table creation on activation
    $table_name_quizset_meta_info = $wpdb->prefix . 'dquizset_meta_info';
    $sql_to_quizset_meta_info = "CREATE TABLE {$table_name_quizset_meta_info} (
        quiz_Set_Id varchar(20),
        quiz_Set_title varchar(255),
        author varchar(255),
        time_limit int(10),
        total_point int(10),
        PRIMARY  KEY  (quiz_Set_Id)
        )";
    dbDelta($sql_to_quizset_meta_info);

  

}
register_activation_hook( __FILE__, 'dquiz_activate' );

//// dquiz deactivation hook
function dquiz_deactivate(){
    global $wpdb;
    $wpdb->query('TRUNCATE TABLE wp_dquiz_questions');
    $wpdb->query($wpdb->prepare( "DROP TABLE IF EXISTS wp_dquiz_questions"));

    $wpdb->query('TRUNCATE TABLE wp_dquiz_ans');
    $wpdb->query($wpdb->prepare( "DROP TABLE IF EXISTS wp_dquiz_ans"));

    $wpdb->query('TRUNCATE TABLE wp_dquizset_meta_info');
    $wpdb->query($wpdb->prepare( "DROP TABLE IF EXISTS wp_dquizset_meta_info"));
}
register_deactivation_hook(__FILE__, 'dquiz_deactivate');


// enquing script 
function dquiz_enqueuing_admin_scripts($hook){
    wp_enqueue_script( 'jquery' );
    // to know the hook name of this page I use
    // echo $current_page = get_current_screen()->base;
     if('d-quiz_page_new_quiz'== $hook){
        wp_enqueue_style('create_quiz-style', plugins_url('/assets/CSS/create_quiz.css',__FILE__),time());
        wp_enqueue_script('create_quiz-script',  plugins_url('/assets/JS/create_quiz.js',__FILE__),null,time(),true); 
        wp_enqueue_script( 'jquery' );   
        wp_localize_script('create_quiz-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
        }  
        if('d-quiz_page_all_quiz'== $hook){
            wp_enqueue_script('all_quiz-script',  plugins_url('/assets/JS/all_quiz.js',__FILE__),'jquery',time(),true);
            wp_localize_script('all_quiz-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
        }
      
}
add_action( 'admin_enqueue_scripts', 'dquiz_enqueuing_admin_scripts' );
function dquiz_enqueuing_scripts($hook){
     wp_register_script('shortcode_dquiz-script',  plugins_url('/assets/JS/short_code.js',__FILE__),'jquery',time(),true);
    wp_localize_script('shortcode_dquiz-script', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
      
      
}
add_action( 'wp_enqueue_scripts', 'dquiz_enqueuing_scripts' );



///// including different part 
require_once 'plugin_pages/create_page.php';
require_once 'actions/insert_quiz.php';
require_once 'short_code/display_quiz.php';


