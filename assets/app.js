import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'bootstrap/dist/css/bootstrap.min.css';
import './fontawesome-free-6.5.2-web/css/all.min.css';
import './styles/app.css';
import './styles/menu.css';
import './styles/footer.css';
import './styles/acceuil.css';
import './styles/habitats.css';
import './styles/service.css';
import './styles/contact.css';


/*
 * Import any other JS files you need here.
 */

import * as bootstrap from 'bootstrap';
window.bootstrap = bootstrap;

import "./js/rating.js";





console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

/*
*choix des habitats pour la page d'haitats
*/

// document.addEventListener("DOMContentLoaded", function () {
//   const links = document.querySelectorAll('.container-link');
//   const sections = document.querySelectorAll('.habitat_section');

//   links.forEach(link => {
//     link.addEventListener('click', function (e) {
//       e.preventDefault(); // Prevent the default link behavior
//       const habitatName = this.getAttribute('data-habitat');

//       // Remove 'active' class and add 'inactive' class to all links
//       links.forEach(lnk => {
//         lnk.classList.remove('active');
//         lnk.classList.add('inactive');
//       });

//       // Add 'active' class and remove 'inactive' class to the clicked link
//       this.classList.add('active');
//       this.classList.remove('inactive');

//       // Hide all sections
//       sections.forEach(section => {
//         section.style.display = 'none';
//       });

//       // Show the selected section
//       document.getElementById(habitatName).style.display = 'block';
//     });
//   });
// });

// // Statistiques des clics sur les animaux
// document.addEventListener('DOMContentLoaded', function () {
//   document.querySelectorAll('.card').forEach(function (card) {
//     card.addEventListener('click', function () {
//       const animalPrenom = card.getAttribute('data-animal-prenom');
//       fetch(`/animal/view/${animalPrenom}`)
//         .then(response => response.json())
//         .then(data => {
//           console.log(`L'animal ${animalPrenom} a été consulté ${data.viewCount} fois.`);
//         });
//     });
//   });
// });


