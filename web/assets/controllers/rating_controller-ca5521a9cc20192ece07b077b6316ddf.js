import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['star', 'ratingInput']

  connect() {
    this.stars = this.starTargets;
    this.ratingInput = this.ratingInputTarget;

    this.stars.forEach(star => {
      star.addEventListener('click', () => {
        const rating = star.getAttribute('data-value');
        this.ratingInput.value = rating;
        this.updateStars(rating);
      });
    });
  }

  updateStars(rating) {
    this.stars.forEach(star => {
      if (star.getAttribute('data-value') <= rating) {
        star.classList.remove('fa-regular');
        star.classList.add('fa-solid');
      } else {
        star.classList.remove('fa-solid');
        star.classList.add('fa-regular');
      }
    });
  }
}
