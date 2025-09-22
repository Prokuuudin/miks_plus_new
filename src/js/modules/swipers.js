import Swiper from 'swiper';
import 'swiper/css';
import 'swiper/css/pagination';

function initSwiper(selector) {
  const container = document.querySelector(selector);
  if (!container) return;

  return new Swiper(container, {
    loop: true,
    speed: 700,
    slidesPerView: 1,
    centeredSlides: true,
    spaceBetween: 20,
    autoplay: { delay: 3000, disableOnInteraction: false },
    pagination: { 
      el: container.querySelector('.swiper-pagination'), 
      clickable: true 
    },
  });
}

export default function initAllSwipers() {
  initSwiper('.swiper-seminars');
  initSwiper('.swiper-exhibitions');
  initSwiper('.swiper-sponsorship');
}
