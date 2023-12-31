const sendData = (path, data) => {
    fetch(path, dccata)
}
const showFormError = (err) => {
     let errorEle = document.querySelector('.error');
     errorEle.innerHTML = err;
     errorEle.classList.add('show')
}