import ScrollReveal from 'scrollreveal';

// Базовые настройки
ScrollReveal({
	distance: '30px',
	duration: 2800,
	// reset: true,
});

function scrollRevealFunc() {
	ScrollReveal().reveal({
		delay: 1300,
		distance: '0px',
		opacity: 0,
	});

	ScrollReveal().reveal({
		delay: 200,
		distance: '0px',
		opacity: 0,
	});

	ScrollReveal().reveal(`.header__row`, {
		origin: 'top',
	});

	ScrollReveal().reveal('.title-2, .vacancies-title, .contacts__info-text ', {
		delay: 400,
		origin: 'top',
	});

	ScrollReveal().reveal(`.about__text, .contacts, .card-front, .store-card-front, .activities__card-front, .footer__company, .swiper, .contacts__links, .activities__image, .vacancies-outro, .contacts__info-text--vacasncies, .contacts__socials--vacasncies`, {

		origin: 'left',
	});

	ScrollReveal().reveal(`.about__details`,
		{
			origin: 'right',
		}
	);

	ScrollReveal().reveal(`.about__content, .footer__copyright, .activities__info, .contacts__socials, .vacancy__form, .contacts__links--vacasncies`, {
		// delay: 600,
		origin: 'bottom',
	});

}

export default scrollRevealFunc;