const elements = document.querySelectorAll('.mix-card-sobre, .mix-about, .mix-title, .mix-produto');

const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('show');
    }
  });
}, { threshold: 0.15 });

elements.forEach(el => {
  el.classList.add('hidden');
  observer.observe(el);
});
