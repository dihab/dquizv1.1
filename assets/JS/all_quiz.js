
document.querySelector('#delete_quizset').addEventListener('click',function(e){


let del_quiz_set = e.target.getAttribute('data-quizsetid')

jQuery(document).ready( function() {
jQuery.ajax({
type : "POST",
url : myAjax.ajaxurl,
data : {
action: "del_quiz_set", 
data_quizset_to_delete :  del_quiz_set, },

success: function(response){
//console.log(del_quiz_set); 
}
}) 
  

})

e.target.parentNode.parentNode.remove();
})
