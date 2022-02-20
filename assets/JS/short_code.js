
    let quizsetid = document.querySelector('.quizsetid').value;
    let all_ans = document.querySelectorAll('.ans');
    let user_ans =[];
    let submit_ans = document.querySelector('.submit_ans');
    for(i=0; i < all_ans.length;  i++){
        all_ans[i].addEventListener('click',function(e){
            
            if(e.target.getAttribute('data_selected')=='false'){
                e.target.checked = true; 
                e.target.setAttribute('data_selected','true')
            }else{
                e.target.checked = false; 
                e.target.setAttribute('data_selected','false');
               let remove_index =  user_ans.indexOf(e.target.getAttribute('id'))
                user_ans.splice(remove_index, 1)
            }
        })  
    }
    // action after submit ans btn clicked
    submit_ans.addEventListener('click',function(e){
           
            for(i=0; i < all_ans.length;  i++){

                if(all_ans[i].checked==true){
                    let ans_to_push = all_ans[i].getAttribute('id');
                    user_ans.push(ans_to_push);
                   // console.log(all_ans[i].getAttribute('data-ans'));
                }                  
            }

            user_ans = [...new Set(user_ans)]
            jQuery(document).ready( function() {

       
                jQuery.ajax({
                type : "POST",
                //dataType : "json",
                url : myAjax.ajaxurl,
                data : 
                {
                    action: "check_ans", 
                    data_user_ans : user_ans,
                    quizsetid: quizsetid
                },

                success: function(response){
                    
                   // console.log(response);
                   // document.querySelector('.number').innerHTML= response;
                  
                   setTimeout(function(){
                    document.querySelector('.quizset_main_body').innerHTML = 'Your Total marks:'+response
                   },1000)

                }


                })   



            })

            

            
    })

    
    

