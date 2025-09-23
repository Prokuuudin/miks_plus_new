import Swiper from 'swiper';
import 'swiper/css';

function initSwiper(selector) {
  const container = document.querySelector(selector);
  if (!container) return;

  return new Swiper(container, {
    slidesPerView: 2,
    centeredSlides: true,
    spaceBetween: 20,
     
  });
}

export default function initAllSwipers() {
  initSwiper('.swiper-seminars');
  initSwiper('.swiper-exhibitions');
  initSwiper('.swiper-sponsorship');
}
