document.querySelectorAll('.brand-card').forEach(card => {
  const front = card.querySelector('.card-front');
  const closeBtn = card.querySelector('.close-btn');

  front.addEventListener('click', () => {
    card.classList.add('flipped');
  });

  closeBtn.addEventListener('click', e => {
    e.stopPropagation();
    card.classList.remove('flipped');
  });
});

import headerScroll from './modules/header-scroll.js';
headerScroll();

import setLanguage from './modules/setLanguage.js';
setLanguage();

import initProjectsAccordion from './modules/projects-accordion.js';
initProjectsAccordion();

import placeholderBehavior from './modules/placeholder-behavior.js';
placeholderBehavior();

import mobileNav from './modules/mobile-nav.js';
mobileNav();

import getSwiper1 from './modules/swiper1.js';
getSwiper1();

import getSwiperMobile from './modules/swiper-mobile.js';
getSwiperMobile();

// import scrollReveal from './modules/scrollReveal.js';
// scrollReveal();

import getModalInfo from './modules/modal-info.js';
getModalInfo();

import getModalShop from './modules/modal-shop.js';
getModalShop();

import getModalForm from './modules/modal-form.js';
getModalForm();

import sendContactsForm from './modules/form-validation.js';
sendContactsForm();

import getSwiperWave from './modules/swiper-wave.js';
getSwiperWave();

import getCookiesConsent from './modules/agreement-cookies.js';
getCookiesConsent();

document.querySelectorAll('.brand-card').forEach(card => {
  const front = card.querySelector('.card-front');
  const closeBtn = card.querySelector('.close-btn');

  front.addEventListener('click', () => {
    // Закрыть все открытые карточки
    document.querySelectorAll('.brand-card.flipped').forEach(openCard => {
      if (openCard !== card) {
        openCard.classList.remove('flipped');
      }
    });
    // Открыть текущую
    card.classList.add('flipped');
  });

  closeBtn.addEventListener('click', e => {
    e.stopPropagation();
    card.classList.remove('flipped');
  });
});
