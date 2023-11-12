// const swiper = new Swiper(".swiper-container", {
//   pagination: ".swiper-pagination",
//   paginationClickable: true,
//   parallax: true,
//   speed: 600,
//   autoplay: 3500,
//   loop: true,
//   grabCursor: true
// });
document.addEventListener("DOMContentLoaded", function () {
	const swiper = new Swiper(".swiper-container", {
		// Optional parameters
		// direction: "vertical",
		loop: true,
		// speed: 600,
		// autoplay: {
		// 	delay: 3500,
		// 	disableOnInteraction: false,
		// },
		grabCursor: true,
		paginationClickable: true,

		//  we need pagination
		pagination: {
			el: ".swiper-pagination",
			clickable: true,
		},

		// Navigation arrows
		// navigation: {
		// 	nextEl: ".swiper-button-next",
		// 	prevEl: ".swiper-button-prev",
		// },

		// And if we need scrollbar
		// scrollbar: {
		// 	el: ".swiper-scrollbar",
		// },
	});
});
