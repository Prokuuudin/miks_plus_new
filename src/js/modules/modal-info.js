function getModalInfo() {
    let openInfoBtns = document.querySelectorAll('.open-info-btn');
    let modalCompanyInfo = document.getElementById('modal-info');
    let closeModalBtn = document.getElementById('modal__close-btn');
    let body = document.querySelector('body');
    let header = document.querySelector('header');
    let widthScroll;
    
    if (openInfoBtns.length > 0) {
        for (let btn of openInfoBtns) {
            btn.onclick = function(){
                modalCompanyInfo.classList.add('modal-info--active');
                widthScroll = window.innerWidth - modalCompanyInfo.offsetWidth + 'px';
                body.style.overflow = 'hidden';
                header.style.overflow = 'hidden';
                header.style.paddingRight = widthScroll;
                body.style.paddingRight = widthScroll;
            }
        }
    }
    
    closeModalBtn.onclick = function(){
        modalCompanyInfo.classList.remove('modal-info--active');
        setTimeout(() => {
            body.style.overflow = 'visible';
            body.style.paddingRight = '0px';
            header.style.overflow = 'visible';
            header.style.paddingRight = '0px';
        }, 800);
    }
    
    window.addEventListener('click', (e) => {
        let target = e.target;
        if (target === modalCompanyInfo){
            modalCompanyInfo.classList.remove('modal-info--active');
            setTimeout(() => {
                body.style.overflow = 'visible';
                body.style.paddingRight = '0px';
                header.style.overflow = 'visible';
                header.style.paddingRight = '0px';
            }, 800);
        }
    });
}
export default getModalInfo;
   
    