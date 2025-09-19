function openBrandCard () {
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

}

export default openBrandCard