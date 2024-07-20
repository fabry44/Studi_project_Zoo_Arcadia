/*
* choix des étoiles pour la page d'avis pour le rating
*/
// document.addEventListener('DOMContentLoaded', function () {
//   const stars = document.querySelectorAll('.rating-star');

//   const ratingInput = document.querySelector('input[name="avis[rating]"]');
//   console.log(ratingInput);
//   console.log(stars);
//   stars.forEach(star => {
//     star.addEventListener('click', function () {
//       console.log('star clicked');
//       const rating = this.getAttribute('data-value');
//       // ratingInput.value = rating;
//       updateStars(rating);
//     });
//   });

//   function updateStars(rating) {
//     stars.forEach(star => {
//       if (star.getAttribute('data-value') <= rating) {
//         star.classList.remove('fa-regular');
//         star.classList.add('fa-solid');
//       } else {
//         star.classList.remove('fa-solid');
//         star.classList.add('fa-regular');
//       }
//     });
//   }
// });