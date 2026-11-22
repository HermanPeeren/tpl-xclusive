/**
 * Xclusive template: "naar boven" (back to top) link.
 * Replaces the jQuery code in j-backbone.js of xclusive-joostrap: the link shows
 * after scrolling down 200px and scrolls smoothly to the top.
 */
(() => {
  'use strict';

  const init = () => {
    const goTop = document.querySelector('.go-top');

    if (!goTop) {
      return;
    }

    const toggle = () => {
      goTop.classList.toggle('is-visible', window.scrollY > 200);
    };

    window.addEventListener('scroll', toggle, { passive: true });
    toggle();

    goTop.addEventListener('click', (event) => {
      event.preventDefault();
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });
  };

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
