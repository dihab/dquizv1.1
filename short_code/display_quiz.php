<?php
//// short code for display quizset 
function display_quiz( $atts, $content, $shortcode_tag ){
    wp_enqueue_script( 'jquery' );
    wp_enqueue_script( 'shortcode_dquiz-script');

    global $wpdb;
    $quiz_setid = $atts['id'];

    $all_qs = $wpdb->get_results("SELECT * FROM wp_dquiz_questions WHERE quiz_Set_Id=$quiz_setid", ARRAY_A);
    $all_ans = $wpdb->get_results("SELECT * FROM wp_dquiz_ans WHERE quiz_Set_Id=$quiz_setid", ARRAY_A);
    
    // echo '<pre>';
    // print_r($all_ans);
    //  echo '</pre>';
 
    
?>

<section class="quizset_main_body">
    <h5 class="number"></h5>
    <input type="hidden" name="" class="quizsetid"  value="<?php echo $quiz_setid; ?>">
    <style>
        .bold{ font-weight: 900;}
    </style>

    <?php
    foreach($all_qs as $qs){
    ?>

        <h3><span class="bold">QS:</span><?php echo $qs['quiz_Qs']; ?></h3>

            <?php
            foreach($all_ans as $ans){
            if($ans['related_quiz_Id']== $qs['quiz_Id']){              
            ?>
                <input class="ans"
                    type="<?php echo $qs['ans_type']; ?>"
                    name="<?php echo $qs['quiz_Id']; ?>" 
                    id="<?php echo $ans['ans_id']; ?>"
                    data-ans=
                    "<?php echo $ans['ans']; ?>"
                    data_selected='false'
                >

                <label for="<?php echo $ans['ans_id']; ?>">
                <?php echo $ans['ans']; ?>
                </label><br>
              
            <?php
            }
            }
            ?>


    <?php
    }
    ?>

<button class='submit_ans'>SUBMIT ANS</button>

</section>
<?php
}

add_shortcode( 'quiz', 'display_quiz' );

//[quiz id=324325]


?>
    