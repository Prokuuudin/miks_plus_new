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

	ScrollReveal().reveal( { 
		delay: 200,
		distance: '0px',
		opacity: 0,
	});

	ScrollReveal().reveal( `.header__row`, {
		origin: 'top',
	});
	
	ScrollReveal().reveal( {
		delay: 400,
		origin: 'top',
	});

	ScrollReveal().reveal( `.title-2`, {
		origin: 'left',
	});

	ScrollReveal().reveal( `.about__details`,
		{
			origin: 'right',
		}
	);

	ScrollReveal().reveal(`.about__content, .about__footer`,  {
		origin: 'bottom',
	});
	
}

export default scrollRevealFunc;