
<?php



function insert_qs_cb() {

    $data_qs = $_REQUEST["data_qs"];
    global $wpdb;  
    if($data_qs){

        foreach($data_qs as $qs){     

                $quiz_Set_Id =  $qs['0'];
                $quiz_Id =  $qs['1'];
                $quiz_Qs =  $qs['2'];
                $ans_type = $qs['3'];

               $updated = $wpdb->update(
                    'wp_dquiz_questions',
                    array(                          
                    'quiz_Qs' => $quiz_Qs,
                    'ans_type'=>  $ans_type
                    ), 
                    array(                          
                        'quiz_Id' => $quiz_Id,
                    ) );

                    if($updated==false){

                       $wpdb->insert (
                            'wp_dquiz_questions',
                                array(                          
                                'quiz_Set_Id' => $quiz_Set_Id,
                                'quiz_Id' => $quiz_Id,
                                'quiz_Qs' => $quiz_Qs,
                                'ans_type'=>  $ans_type,
                                )
                        );

                    }
                
            

                


        }

    }
     ////////////////////////////////////////////////////////
    $data_ans = $_REQUEST["data_ans"];
    if($data_ans){
        foreach($data_ans as $anss){     

                $quiz_Set_Id =  $anss['0'];
                $quiz_Id = $anss['1'];
                $ans_id = $anss['2'];
                $ans = $anss['3'];
                $ans_selected = $anss['4'];
                $ans_point = $anss['5'];
        


                   $updated = $wpdb->update(
                    'wp_dquiz_ans',
                    array(                          
                    'ans' => $ans,
                    'ans_selected' => $ans_selected,
                    'ans_point' => $ans_point
                    ), 
                    array(                          
                        'ans_id' => $ans_id,
                    ) );

                    if($updated==false){

                        $wpdb->insert (
                            'wp_dquiz_ans',
                                array(                          
                                'quiz_Set_Id' => $quiz_Set_Id,
                                'related_quiz_Id' => $quiz_Id,
                                'ans_id' => $ans_id,
                                'ans'=>  $ans,
                                'ans_selected' => $ans_selected,
                                'ans_point' => $ans_point
                                )
                        );

                    }
                
                        


        }

    }  

   
    $data_quiz_set_meta_info = $_REQUEST["data_quiz_set_meta_info"];

    //print_r($data_quiz_set_meta_info);

     $quiz_set_id = $data_quiz_set_meta_info['0'];
     $quizset_title = $data_quiz_set_meta_info['1'];
     $quiz_SET_author = get_current_user_id();
     $time_limit = $data_quiz_set_meta_info['2'];
    

    $quiz_ans_point_array = $wpdb->get_col( "SELECT ans_point FROM wp_dquiz_ans WHERE  quiz_Set_Id =$quiz_set_id AND ans_selected = 1");
    //print_r($quiz_ans_point_array);

    if(isset($quiz_ans_point_array)){

        $total_point = array_sum($quiz_ans_point_array);  
        $updated = $wpdb->update(
            'wp_dquizset_meta_info',
            array(                          
            'quiz_Set_title' =>  $quizset_title,
            'author' =>$quiz_SET_author,
            'time_limit' => $time_limit,
            'total_point' => $total_point,
            ), 
            array(                          
                'quiz_Set_Id' => $quiz_set_id,
            ) );
    
            if($updated==false && isset($quiz_ans_point_array)){
    
                $wpdb->insert (
                    'wp_dquizset_meta_info',
                    array(                          
                        'quiz_Set_Id' => $quiz_set_id,
                        'quiz_Set_title' => $quizset_title,
                        'author' => $quiz_SET_author,
                        'time_limit' => $time_limit,
                        'total_point' => $total_point,
                    )
                );
    
            }
    }  


    
}
add_action('wp_ajax_insert_qs', 'insert_qs_cb');



function del_qs_cb(){
    $data_qs = $_REQUEST["data_qs_to_delete"];
    global $wpdb; 
    //wp_dquiz_ans
    $wpdb->delete(
        'wp_dquiz_questions', 
        array(
            'quiz_id' => $data_qs // value in column to target for deletion
        )
    );
    $wpdb->delete(
        'wp_dquiz_ans', 
        array(
            'related_quiz_id' => $data_qs // value in column to target for deletion
        )
    );
}
add_action('wp_ajax_del_qs', 'del_qs_cb');



function del_ans_cb(){
    $data_ans = $_REQUEST["data_ans_to_delete"];
    global $wpdb; 
    //wp_dquiz_ans
    
    $wpdb->delete(
        'wp_dquiz_ans', 
        array(
            'ans_id' => $data_ans // value in column to target for deletion
        )
    );
}
add_action('wp_ajax_ans_qs', 'del_ans_cb');



function del_quiz_set_cb(){

    $quizsetID = $_REQUEST["data_quizset_to_delete"];
    global $wpdb; 
    
    $wpdb->delete(
        'wp_dquiz_ans', 
        array(
            'quiz_Set_Id' => $quizsetID // value in column to target for deletion
        )
    );
    $wpdb->delete(
        'wp_dquiz_questions', 
        array(
            'quiz_Set_Id' => $quizsetID // value in column to target for deletion
        )
    );
    $wpdb->delete(
        'wp_dquizset_meta_info', 
        array(
            'quiz_Set_Id' => $quizsetID // value in column to target for deletion
        )
    );

}

add_action('wp_ajax_del_quiz_set', 'del_quiz_set_cb');


function check_ans_cb(){
    $user_point = 0;

    $user_ans = $_REQUEST["data_user_ans"];
    $quizsetid = $_REQUEST["quizsetid"];
    global $wpdb; 
    $all_right_ans = $wpdb->get_results("SELECT ans_id,ans_point FROM wp_dquiz_ans WHERE quiz_Set_Id=$quizsetid AND ans_selected=1 ", ARRAY_A);
    $all_optomized_right_ans =[];

    foreach($all_right_ans as $right_ans){
        $all_optomized_right_ans[$right_ans['ans_id']] = $right_ans['ans_point'];
    }

    // echo '<pre>';
    // print_r($all_optomized_right_ans);
    // echo '</pre>';

    foreach($user_ans as $ans){
     
        if(isset($all_optomized_right_ans[$ans])){
            $user_point = $user_point + $all_optomized_right_ans[$ans];

               //echo '<br>'.$user_point = $user_point + $all_optomized_right_ans[$ans];
         
        }else{
            $user_point = $user_point + 0;
        }
       
    }
    
    echo $user_point;
    exit;
    
    

}

add_action('wp_ajax_check_ans', 'check_ans_cb');

?>