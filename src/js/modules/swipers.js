import Swiper from 'swiper';
import { Navigation } from 'swiper/modules'; // 👈 импортируем модуль навигации
import 'swiper/css';
import 'swiper/css/navigation'; // 👈 стили кнопок навигации

function initSwiper(selector) {
  const container = document.querySelector(selector);
  if (!container) return;

  return new Swiper(container, {
    modules: [Navigation],
    loop: true,
    slidesPerView: 1,
    centeredSlides: true,
    spaceBetween: 20,

    navigation: {
      nextEl: container.querySelector('.swiper__btn--next'),
      prevEl: container.querySelector('.swiper__btn--prev'),
    },
  });
}

export default function initAllSwipers() {
  initSwiper('.swiper-seminars');
  initSwiper('.swiper-exhibitions');
  initSwiper('.swiper-sponsorship');
}
