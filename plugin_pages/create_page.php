<?php

// create option page link in dashboard
function dquiz_create_pages(){

    //add_menu_page($page_title,$menu_title,$capability,$slug,$callback);
    add_menu_page(__('dquiz','dquiz'),__('D-QUIZ','dquiz'),'manage_options','dquiz','dquiz_main_page_contents');

    // add_submenu_page( string $parent_slug, string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = '', int $position = null )
    add_submenu_page( 'dquiz', 'new quiz', 'New Quiz', 'manage_options', "new_quiz", 'dquiz_new_quiz_page_html', null );
    add_submenu_page( 'dquiz', 'all quiz', 'ALL Quizs', 'manage_options', "all_quiz", 'dquiz_all_quiz_page_html', null );
   
}
add_action('admin_menu','dquiz_create_pages');





// html for dquiz main page conents
function dquiz_main_page_contents(){ }



// html for all quiz page 
function dquiz_all_quiz_page_html(){
    $edit_link = admin_url().'admin.php?page=new_quiz&quiz_set_id=' ;
    global $wpdb;
    $user = get_current_user_id();
    $all_quizset = $wpdb->get_results("SELECT * FROM wp_dquizset_meta_info WHERE author = {$user}", ARRAY_A);



    ?>
    <style>
        *{box-sizing: border-box ; margin: 0; padding: 0;}
        h2{color:brown; font-weight: 800;}
        .heading{background-color: yellow;}
        .s_item{background-color:coral;}
        .hg{padding: 8px;border: 1px solid black; font-weight: 800;}
        .fi{padding: 8px;border: 1px solid black; font-weight: 400; color:whitesmoke; font-size: 16px;}
        .single_quiz_row{display: flex;}
        .quiz_id_container{width: 15%;}  
        .quiz_title_container{width: 20%;}
        .quiz_author_container{width: 10%;}
        .quiz_time_container{width: 9%;}
        .quiz_ponit_container{width: 7%;}
        .quiz_shorcode_container{width: 20%;}
        .quiz_action_container{width: 20%;}
    </style>
    
    <h2>ALL QUIZ SET </h2>
        <div class="all_quiz_set">
            <div class="single_quiz_row heading">
                <div class="quiz_id_container hg">QUIZ ID</div>
                <div class="quiz_title_container hg">QUIZ TITLE</div>
                <div class="quiz_author_container hg">QUIZ CREATOR</div>
                <div class="quiz_time_container hg ">TIME LIMIT</div>
                <div class="quiz_ponit_container hg">TOTAL POINT</div>
                <div class="quiz_shorcode_container hg">SHORT CODE</div>
                <div class="quiz_action_container hg">ACTION</div>
            </div>
        </div>
    <div class="all_quiz_set s_item">
        <?php 

        foreach($all_quizset as $quizset){
        ?>
        <div class="single_quiz_row">
            <div class="quiz_id_container fi"><?php echo $quizset['quiz_Set_Id']; ?></div>
            <div class="quiz_title_container fi"><?php echo $quizset['quiz_Set_title']; ?></div>
            <div class="quiz_author_container fi"><?php echo $quizset['author']; ?></div>
            <div class="quiz_time_container fi"><?php echo $quizset['time_limit'].'sec'; ?></div>
            <div class="quiz_ponit_container fi"><?php echo $quizset['total_point']; ?></div>
            <div class="quiz_shorcode_container fi"><?php echo '[quiz id='.$quizset['quiz_Set_Id'].']'; ?></div>
            <div class="quiz_action_container fi">
                <button id="edite_quizset"> <a href="<?php echo $edit_link.$quizset['quiz_Set_Id'] ?>">EDITE</a> </button>
                <button id="delete_quizset" data-quizsetid="<?php echo $quizset['quiz_Set_Id']; ?>">DELETE</button>
            </div>           
        </div>
    <?php
     }
    ?>  
    <span>
    </span>            
    </div>

    <?php
}



// html for dquiz add new quiz page conents
function dquiz_new_quiz_page_html(){

    $user_id = get_current_user_id(); 
    wp_localize_script( 'create_quiz-script', 'user_info', array( 'user_id' => $user_id) );
   
    
    ?>
            <style>
                .message_to_wait{
                    position: absolute;
                    z-index: 3245646;
                    bottom:100px;
                    left:30%;
                    font-size: 70px;
                    }
            </style>
    <p class="message_to_wait">QUIZSET SAVING....</p>
            
            <section class="quizset ">
            
                    <div class="quizset__matainfo border10">
                        <h3 class="quizset_id my1">
                        <span class="htxt">QUIZ ID :</span> 
                        <span id="quiz_set_id"> </span>    
                        </h3>
                        <?php
                        
                            global $wpdb;


                                if(isset($_REQUEST['quiz_set_id']) && is_numeric($_REQUEST['quiz_set_id'])){

                                     $quiz_setid = $_REQUEST['quiz_set_id'];
                                     $quiz_meta = $wpdb->get_row("SELECT * FROM wp_dquizset_meta_info WHERE quiz_Set_Id= {$quiz_setid}",ARRAY_A);

                                     if(!$quiz_meta['author']==get_current_user_id()){
                                        die('invalid access detected');
                                     }
                                                   
                                    ?>
                                    <span class="htxt displayBlock mtop1">QUIZ SET TITLE : </span>
                                    <textarea name="quizset__title" id="quizset__title" class="quizset__title my1" required cols="100" rows="2"><?php 
                                    if(isset($quiz_meta['quiz_Set_title'])){
                                    echo $quiz_meta['quiz_Set_title']; }
                                    ?></textarea>
                                    <span class="htxt displayBlock mtop1">QUIZ TIME(in second) : </span>
                                    <input type="number" name="quizset__time" id="quizset__time" class="mtop1" placeholder="time in second" value="<?php 
                                    if(isset($quiz_meta['time_limit'])){
                                        echo $quiz_meta['time_limit']; }
                                    ?>">



                                     <?php  
                             }else{
                                 ?>
                                <span class="htxt displayBlock mtop1">QUIZ SET TITLE : </span>
                                <textarea name="quizset__title" id="quizset__title" class="quizset__title my1" required cols="100" rows="2"></textarea>
                                <span class="htxt displayBlock mtop1">QUIZ TIME(in second) : </span>
                                <input type="number" name="quizset__time" id="quizset__time" class="mtop1" placeholder="time in second">
                                <?php
                             }

                                
                           
                           ?>
                        
                        
                    </div>
                    <div class="quizitems my1" id="quizitems">
                        

                        <?php
                       
                       
                         global $wpdb;

                         if(isset($_REQUEST['quiz_set_id']) && is_numeric($_REQUEST['quiz_set_id'])){

                            $quiz_setid = $_REQUEST['quiz_set_id'];

                            $all_qs = $wpdb->get_results("SELECT * FROM wp_dquiz_questions WHERE quiz_Set_Id=$quiz_setid", ARRAY_A);

                            $all_ans = $wpdb->get_results("SELECT * FROM wp_dquiz_ans WHERE quiz_Set_Id=$quiz_setid", ARRAY_A);

                            foreach($all_qs as $qs){
                                ?>
                              <div class="quizitem my1" data-quiz-id="<?php echo $qs['quiz_Id']?>">
                                      <span class="del_quiz">DELETE QUIZ</span>
                                      
                                      <span class="htxt my1">Qs:</span>
                                      <textarea name="" data-quiz_set_id="<?php echo $qs['quiz_Set_Id']?>" data-quiz-id="<?php echo $qs['quiz_Id']?>" cols="100" rows="2" class="displayBlock my1 quizitem_qs" anstype="<?php echo $qs['ans_type']?>"><?php echo trim($qs['quiz_Qs']); ?></textarea>
                                  
                                      <span class="my1">ANS TYPE:</span>
                                     
  
                                      <input type="radio" <?php echo $qs['ans_type']=='checkbox'?'checked':''; ?> name="<?php echo time().$qs['quiz_Id']; ?>" id="<?php echo '1'.time().$qs['quiz_Id']; ?>" class="multiple_ans">
                                      <label for="<?php echo '1'.time().$qs['quiz_Id']; ?>">Multiple</label>
  
                                      <input type="radio"  
                                      <?php echo $qs['ans_type']=='radio'?'checked':''; ?>
                                       name="<?php echo time().$qs['quiz_Id']; ?>" id="<?php echo '2'.time().$qs['quiz_Id']; ?>" class="single_ans"> 
                                      <label for="<?php echo '2'.time().$qs['quiz_Id']; ?>">SINGLE</label>
                                      
                                      
  
                                      <div class="quizitem_ans_set" data-quiz-id="<?php echo $qs['quiz_Id']?>">
  
                                      <?php
  
                                     
                                      foreach($all_ans as $ans){
                                          
                                          if($ans['related_quiz_Id']==$qs['quiz_Id']){
                                              ?>
  
                                              <div class="singel_ans"  data-ans-id=<?php echo $ans['ans_id']; ?>>
  
                                                      <input <?php echo $ans['ans_selected']=='1'?'checked':''; ?> type="<?php echo $qs['ans_type']?>" name="<?php echo $qs['quiz_Id']?>" id="" class="check_ans my1">
                                              
                                                      <input type="text" value="<?php echo $ans['ans']; ?>" class="quizitem_ans my1" right_ans="<?php echo $ans['ans_selected']; ?>" data-ans-id="<?php echo $ans['ans_id']; ?>" data-quiz-id="<?php echo $ans['related_quiz_Id']; ?>" data-quiz_set_id="<?php echo $ans['quiz_Set_Id']; ?> " point="<?php echo $ans['ans_point']; ?>" >
                                              
                                                      <input type="number" class="ans_point my1" value="<?php echo $ans['ans_point']; ?>">
                                              
                                                      <span class="del_ans">DELETE ANS</span>
                                              
                                               </div>
  
  
                                      <?php
                                          }
                                     
                                      
                                      }
                                      
  
                                      ?>
  
                                      </div>
  
  
  
                                  <button class="add_ans_btn btn my1">add new ans</button>
  
                              </div>     
                           <?php
                            }
                         }else{
                            // echo ' no quiz created for . start creating';
                         }

                        

                       

                      

                        



                        ?>
                    

                       </div>
                    <button  class="my1 displayBlock btn border10 add_new_quiz_btn"  id="add_new_quiz_btn">ADD NEW QUIZ</button>
                    <button type="submit" class="displayBlock my1 btn save_quiz_set border10" id="save_quiz_set">SAVE QUIZ SET</button>

            </section>


    <?php
 }











?>