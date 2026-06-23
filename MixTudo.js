
const elements = document.querySelectorAll('.mix-card, .mix-about, .mix-title');

const observer = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if(entry.isIntersecting){
      entry.target.classList.add('show');
    }
  });
});

elements.forEach(el => {
  el.classList.add('hidden');
  observer.observe(el);
});
