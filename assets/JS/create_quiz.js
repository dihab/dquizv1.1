


// if you are redirecting from edit quiz link you have quiz id and get it in url search string
// then we will use that id
let searchParams = new URLSearchParams(window.location.search);
var quiz_set_id = searchParams.get('quiz_set_id');

// /// if you are redirecting from add new quiz link you have no quiz id and you have to set it 
if(quiz_set_id){ // when quiz id found ** that means you come from edit quiz link **

    document.getElementById('quiz_set_id').innerHTML  = searchParams.get('quiz_set_id')
    
}else{ // when quiz id not found  ** that means you come from add new quiz link **

  
    
    const url = new URL(window.location);
    let uniq_id =  Date.now()+user_info.user_id;
    url.searchParams.set('quiz_set_id',uniq_id);
    window.history.pushState({}, '', url);

    document.getElementById('quiz_set_id').innerHTML  = url.searchParams.get('quiz_set_id');

}


//quizset
let quizset = document.querySelector('.quizset');
// save quiz button
let save_quizset_btn = document.getElementById('save_quiz_set');
// add new quiz button 
let add_new_quiz_btn = document.getElementById('add_new_quiz_btn');
// add new ans button 
let add_new_ans_btn = document.querySelectorAll('.add_ans_btn');
// quiz items
let quiz_items = document.getElementById('quizitems');
// save quiz set button
let save_quiz_set =  document.getElementById('save_quiz_set'); 


document.querySelector('.message_to_wait').style.display ='none';
// action after click add_new_quiz_btn button

add_new_quiz_btn.addEventListener('click',function(){
    let searchParams = new URLSearchParams(window.location.search);
    let quiz_set_Id = searchParams.get('quiz_set_id');

    let quiz_id = Date.now()+user_info.user_id;

    quiz_items.insertAdjacentHTML('beforeend',`

    <div class="quizitem my1" data-quiz-id = ${quiz_id}>
            <span class='del_quiz'>DELETE QUIZ</span>
            
            <span class="htxt my1">Qs:</span>
            <textarea name="" data-quiz_set_id = ${quiz_set_Id}  data-quiz-id = ${quiz_id} cols="100" rows="2" class="displayBlock my1 quizitem_qs" anstype ='checkbox' > Your Qestion </textarea>
          
            <span class='my1'>ANS TYPE:</span>
            <input type="radio" checked name="${Date.now()+2}" id="${Date.now()+2}" class="multiple_ans">
             <label for="${Date.now()+2}">Multiple</label>
            <input type="radio" name="${Date.now()+2}" id="${Date.now()+1}" class="single_ans"> 
            <label for="${Date.now()+1}">SINGLE</label>
            
            

            <div class="quizitem_ans_set"  data-quiz-id = ${quiz_id}>

            </div>

           <button class="add_ans_btn btn my1">add new ans</button>

   </div> 
 `)

})

   
// action after click add_ans_btn button
quiz_items.addEventListener('click',function(e){

            if(e.target.classList.contains('add_ans_btn')){
                let qtype ;

                if(e.target.parentNode.querySelector('.multiple_ans').checked){
                    qtype = 'checkbox';
                }

                if(e.target.parentNode.querySelector('.single_ans').checked){
                    qtype = 'radio';
            }
                
            
            e.target.parentNode.querySelector('.quizitem_ans_set').insertAdjacentHTML('beforeend',`
            <div class="singel_ans" data-ans-id = ${Date.now()} >

                <input type="${qtype}" name="${e.target.parentNode.querySelector('.quizitem_qs').getAttribute('data-quiz-id')}" id="" class="check_ans my1" >

                <input type="text" placeholder="write your ans" class="quizitem_ans my1"  right_ans ='0' data-ans-id = ${Date.now()} data-quiz-id = ${e.target.parentNode.querySelector('.quizitem_qs').getAttribute('data-quiz-id')} data-quiz_set_id = ${e.target.parentNode.querySelector('.quizitem_qs').getAttribute('data-quiz_set_id')} >

                <input type="number" class="ans_point my1" placeholder="Point for this ans." >

                <span class='del_ans'>DELETE ANS</span>

                </div>
            `)
            }

})

// action after click check_ans_btn button
quiz_items.addEventListener('click',function(e){

    if(e.target.classList.contains('check_ans')){
        

         ////for radio type
  
        if(e.target.type=='radio'){
            if(e.target.nextElementSibling.getAttribute('right_ans')=='1')
            {
                let all_sibling_ans = e.target.parentNode.parentNode.querySelectorAll('.quizitem_ans');
                for(i=0;i<all_sibling_ans.length;i++){
                    all_sibling_ans[i].setAttribute('right_ans','0');
                    all_sibling_ans[i].classList.remove('right_ans')

                    console.log( all_sibling_ans[i])
                }
                e.target.nextElementSibling.setAttribute('right_ans','1')
                e.target.nextElementSibling.classList.add('right_ans')
            }else{
                let all_sibling_ans = e.target.parentNode.parentNode.querySelectorAll('.quizitem_ans');
                for(i=0;i<all_sibling_ans.length;i++){
                    all_sibling_ans[i].setAttribute('right_ans','0')
                    all_sibling_ans[i].classList.remove('right_ans')

                    console.log( all_sibling_ans[i])
                }
                e.target.nextElementSibling.setAttribute('right_ans','1')
                e.target.nextElementSibling.classList.add('right_ans')
                
            } 
        }

        ////for checkbox type
        if(e.target.type=='checkbox'){

            if( e.target.nextElementSibling.getAttribute('right_ans')=='1'){
                e.target.nextElementSibling.classList.remove('right_ans')
                // remove the ans from right ans database too
                e.target.nextElementSibling.setAttribute('right_ans','0')
            }else{
                e.target.nextElementSibling.classList.add('right_ans');
                e.target.nextElementSibling.setAttribute('right_ans','1')
            }

        }

       


       
       
    }

})

// action after click del_ans_btn button
quiz_items.addEventListener('click',function(e){

    if(e.target.classList.contains('del_ans')){

        let ans_to_delete = e.target.parentNode.getAttribute('data-ans-id')

        jQuery(document).ready( function() {

       
            jQuery.ajax({
               type : "POST",
               url : myAjax.ajaxurl,
               data : 
               {
                   action: "del_ans", 
                   data_ans_to_delete :  ans_to_delete,
                   
               },
 
               success: function(response){
                 //console.log(quiz_set_meta_info)
                  //console.log(response)
               }
 
             
            })   
      
        
      
      })


        //delete the ans from database using its id
        
       e.target.parentNode.remove();
    }

})

// action after click del_quiz_btn button
quiz_items.addEventListener('click',function(e){

    if(e.target.classList.contains('del_quiz')){

        let qs_to_delete = e.target.parentNode.getAttribute('data-quiz-id')

        jQuery(document).ready( function() {

       
            jQuery.ajax({
               type : "POST",
               url : myAjax.ajaxurl,
               data : 
               {
                   action: "del_qs", 
                   data_qs_to_delete :  qs_to_delete,
                   
               },
 
               success: function(response){
                 //console.log(quiz_set_meta_info)
                  //console.log(response)
               }
 
             
            })   
      
        
      
      })

        //delete the quiz its ques and ans  from database using its id
       e.target.parentNode.remove();
    }

})

// action after click ans_type_btn button
quiz_items.addEventListener('click',function(e){

    if(e.target.classList.contains('multiple_ans')){

       let all_inputs = e.target.parentNode.querySelector('.quizitem_ans_set').querySelectorAll('.check_ans');
       all_inputs.forEach(element => {
                element.type = 'checkbox';   
       });

       e.target.parentNode.querySelector('.quizitem_ans_set').setAttribute('qtype', 'checkbox')
       e.target.parentNode.querySelector('.quizitem_qs').setAttribute('anstype', 'checkbox')   

    }

    if(e.target.classList.contains('single_ans')){

        let all_inputs = e.target.parentNode.querySelector('.quizitem_ans_set').querySelectorAll('.check_ans');
        all_inputs.forEach(element => {
                 element.type = 'radio';   
        });
        e.target.parentNode.querySelector('.quizitem_ans_set').setAttribute('qtype', 'redio')
        e.target.parentNode.querySelector('.quizitem_qs').setAttribute('anstype', 'radio')
     }


})

// action after changing point of quiz ans 
quiz_items.addEventListener('change',function(e){

    if(e.target.classList.contains('ans_point')){
       e.target.previousElementSibling.setAttribute('point',e.target.value);             
    }
})


//action after save button click
save_quiz_set.addEventListener('click',function(e){

    /// getting all questions

    let all_qs_values = document.querySelectorAll('.quizitem_qs');
    let all_qs_data = [];
    for( i = 0; i< all_qs_values.length; i++){

       let qs_id =  all_qs_values[i].getAttribute('data-quiz-id');
       let quiz_set_id =  all_qs_values[i].getAttribute('data-quiz_set_id');
       let qs_val = all_qs_values[i].value;
       let ans_type =  all_qs_values[i].getAttribute('anstype');
      
    
    let single_qs_entry = [quiz_set_id,qs_id,qs_val,ans_type]
         all_qs_data.push(single_qs_entry);
    }
   

     /// getting all ans

     let all_ans_values = document.querySelectorAll('.quizitem_ans');
     let all_ans_data = [];
     for( i = 0; i< all_ans_values.length; i++){
     
        let quiz__set_id =  all_ans_values[i].getAttribute('data-quiz_set_id');
        let quiz_id =  all_ans_values[i].getAttribute('data-quiz-id');
        let ans_id =  all_ans_values[i].getAttribute('data-ans-id');
        let ans = all_ans_values[i].value;
        let ans_selected = all_ans_values[i].getAttribute('right_ans');
        let ans_point = all_ans_values[i].getAttribute('point');
       
     
        let single_ans_entry = [quiz__set_id,quiz_id,ans_id,ans,ans_selected,ans_point]
        all_ans_data.push(single_ans_entry);
     }
    
   /// getting quiz metaset mata info
   let quiz_SET_id = document.getElementById('quiz_set_id').innerHTML;
   let quiz_SET_title = document.getElementById('quizset__title').value;
   let quiz_SET_timelimit = document.getElementById('quizset__time').value;
   
   let quiz_set_meta_info = [quiz_SET_id,quiz_SET_title,quiz_SET_timelimit];
   

    jQuery(document).ready( function() {

       
           jQuery.ajax({
              type : "POST",
              //dataType : "json",
              url : myAjax.ajaxurl,
              data : 
              {
                  action: "insert_qs", 
                  data_qs :  all_qs_data,
                  data_ans :  all_ans_data,
                  data_quiz_set_meta_info: quiz_set_meta_info
              },

              success: function(response){
                //console.log(quiz_set_meta_info)
                 //console.log(response)
              }

            
           })   
     
       
     
     })


    document.querySelector('.message_to_wait').style.display = 'block';
     setTimeout(() => {
        document.querySelector('.message_to_wait').style.display =' none';
     }, 2000);



     
  

})

































              


                    
 
        

