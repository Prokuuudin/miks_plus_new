import SwiperMobile from 'swiper/bundle';
import 'swiper/css/bundle';

function getSwiperMobile() {
    new SwiperMobile (".swiper-mobile", {
      direction: 'horizontal',
      autoplay: true,
      loop: true,
      speed: 700,
      initialSlide: 0,
      freeMode: true,
      slidesPerView: 1,
      centeredSlides: true,
      spaceBetween: 20,

      pagination: {
        el: '.swiper-pagination',
      },
    
  });
}
export default getSwiperMobile;