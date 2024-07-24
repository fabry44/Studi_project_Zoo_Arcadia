// assets/js/controllers/animal_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['card'];

  connect() {
    this.cards = this.cardTargets;

    this.cards.forEach(card => {
      card.addEventListener('click', this.handleClick.bind(this));
    });
  }

  handleClick(event) {
    event.preventDefault();
    const card = event.currentTarget;
    const animalPrenom = card.getAttribute('data-animal-prenom').trim();

    fetch(`/animal/view/${animalPrenom}`)
      .then(response => response.json())
      .then(data => {
        console.log(`L'animal ${animalPrenom} a été consulté ${data.viewCount} fois.`);
      });
  }
}
