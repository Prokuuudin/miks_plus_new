function getModalShop() {
        let openShopBtns = document.querySelectorAll('.open-shop-btn');
        let modalShopInfo = document.getElementById('modal-shop');
        let closeModalBtn = document.getElementById('modal__shop__close-btn');
        let body = document.querySelector('body');
        let header = document.querySelector('header');
        let widthScroll;      

        if (openShopBtns.length > 0) {
            for (let btn of openShopBtns) {
                btn.onclick = function(){
                    modalShopInfo.classList.add('modal-shop--active');
                    widthScroll = window.innerWidth - modalShopInfo.offsetWidth + 'px';
                    body.style.overflow = 'hidden';
                    header.style.overflow = 'hidden';
                    header.style.paddingRight = widthScroll;
                    body.style.paddingRight = widthScroll;
                }
            }
        }
        
        closeModalBtn.onclick = function(){
            modalShopInfo.classList.remove('modal-shop--active');
            setTimeout(() => {
                body.style.overflow = 'visible';
                body.style.paddingRight = '0px';
                header.style.overflow = 'visible';
                header.style.paddingRight = '0px';
            }, 800);
        }
        
        window.addEventListener('click', (event) => {
            let target = event.target;
            if (target === modalShopInfo){
                modalShopInfo.classList.remove('modal-shop--active');
                setTimeout(() => {
                    body.style.overflow = 'visible';
                    body.style.paddingRight = '0px';
                    header.style.overflow = 'visible';
                    header.style.paddingRight = '0px';
                }, 800);
            }
        });
    }
   
    export default getModalShop;
   