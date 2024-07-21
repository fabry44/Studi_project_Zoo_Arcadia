// assets/js/controllers/habitat_controller.js
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
  static targets = ['link', 'section']

  connect() {
    this.links = this.linkTargets;
    this.sections = this.sectionTargets;

    this.links.forEach(link => {
      link.addEventListener('click', this.handleClick.bind(this));
    });
  }

  handleClick(event) {
    event.preventDefault(); // Prevent the default link behavior
    const link = event.currentTarget;
    const habitatName = link.getAttribute('data-habitat');

    // Remove 'active' class and add 'inactive' class to all links
    this.links.forEach(lnk => {
      lnk.classList.remove('active');
      lnk.classList.add('inactive');
    });

    // Add 'active' class and remove 'inactive' class to the clicked link
    link.classList.add('active');
    link.classList.remove('inactive');

    // Hide all sections
    this.sections.forEach(section => {
      section.style.display = 'none';
    });

    // Show the selected section
    document.getElementById(habitatName).style.display = 'block';
  }
}
