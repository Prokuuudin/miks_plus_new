// 
function mobileNav() {
	// Mobile nav button
	const navBtn = document.querySelector('.mobile-nav-btn');
	const nav = document.querySelector('.mobile-nav');
	const menuIcon = document.querySelector('.nav-icon');
	const navLinks = document.querySelectorAll('.mobile-nav a'); // Ссылки в меню

	// Функция для закрытия меню
	const closeMenu = () => {
		nav.classList.remove('mobile-nav--open');
		menuIcon.classList.remove('nav-icon--active');
		document.body.classList.remove('no-scroll');
	};

	// Открытие/закрытие меню по кнопке
	navBtn.onclick = function () {
		nav.classList.toggle('mobile-nav--open');
		menuIcon.classList.toggle('nav-icon--active');
		document.body.classList.toggle('no-scroll');
	};

	// Закрытие меню при клике на пункт меню
	navLinks.forEach(link => {
		link.addEventListener('click', closeMenu);
	});

	// Закрытие меню при клике вне меню
	document.addEventListener('click', (e) => {
		// Проверяем, что клик был не по меню и не по кнопке
		if (!nav.contains(e.target) && !navBtn.contains(e.target)) {
			closeMenu();
		}
	});
}

export default mobileNav;