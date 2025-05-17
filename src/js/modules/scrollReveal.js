import ScrollReveal from 'scrollreveal';

// Базовые настройки
ScrollReveal({
	distance: '30px',
	duration: 2800,
	// reset: true,
});

function scrollRevealFunc () {
	ScrollReveal().reveal( {
		delay: 1300,
		distance: '0px',
		opacity: 0,
	});

	ScrollReveal().reveal(`.about__text`,  {
		delay: 900,
		distance: '0px',
		opacity: 0,
	});

	ScrollReveal().reveal(`.about__title, .brands__title-group, .proposals__title-group, .contacts__title-group `, {
		origin: 'top',
	});
	
	ScrollReveal().reveal(`.header`, {
		delay: 400,
		origin: 'top',
	});

	ScrollReveal().reveal(` .proposals__customers, .contacts__data, .footer__left-side`, {
		origin: 'left',
	});

	ScrollReveal().reveal( ` `,
		{
			origin: 'right',
		}
	);

	ScrollReveal().reveal(`.about__btns, .benefits__wrapper, .brands__desc, .visual__img, .visual__desc, .proposals__corporates, .contacts__connect, footer, .footer__right-side`,  {
		origin: 'bottom',
	});
	
}

export default scrollRevealFunc;