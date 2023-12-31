//form
let formBtn= document.querySelector('.submit-btn');
let loader= document.querySelector('.loader');

formBtn.addEventListener('click' ,() =>{
    let fullname = document.querySelector('#name') || null;
    let email = document.querySelector('#email');
    let password = document.querySelector('#password');
    let numbber = document.querySelector('#number') ||null;
    let tac = document.querySelector('#tc') ||null;
 
    if(fullname !== null){   

 if(fullname.value.length < 3){
    showFormError('name must be 3 letters long');
 } else if(!email.value.length){
     showFormError('enter your email');
 }else if(password.value.length < 8){
    showFormError('password must be 8 letters long');
}else if(Number(numbber) || numbber.value.length <9){
    showFormError('invalid number, please enter valid one');
}else if(!tac.checked){
    showFormError('You must agree to our Terms and Conditions ');
} else{
    //submit form

}

    }else{
        if(!email.value.length || !password.value.length){
            showFormError('fill all the inputs');
        }
    }
});