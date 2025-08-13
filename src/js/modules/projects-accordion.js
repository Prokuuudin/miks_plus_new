function initProjectsAccordion() {

document.querySelectorAll('.project').forEach(project => {
    const header = project.querySelector('.project-header');
    const wrapper = project.querySelector('.project-content-wrapper');

    header.addEventListener('click', () => {
      const isOpen = wrapper.style.height && wrapper.style.height !== "0px";

      if (isOpen) {
        wrapper.style.height = wrapper.scrollHeight + 'px'; // reset height before collapse
        requestAnimationFrame(() => {
          wrapper.style.height = '0px';
        });
        project.classList.remove('open');
      } else {
        wrapper.style.height = wrapper.scrollHeight + 'px';
        project.classList.add('open');
      }
    });

    wrapper.addEventListener('transitionend', () => {
      if (wrapper.style.height === '0px') {
        wrapper.style.height = '';
      }
    });
  });
}

export default initProjectsAccordion;