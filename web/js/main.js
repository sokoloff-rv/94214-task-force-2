const overlay = document.querySelector('.overlay');
const popup = document.querySelector('.pop-up');

const actionButtons = document.querySelectorAll('.action-btn');

actionButtons.forEach(function (el) {
	el.addEventListener('click', function (evt) {
		const modalType = evt.target.dataset.action;
		const modal = document.querySelector('.pop-up--' + modalType);
		modal.classList.remove('pop-up--close');
		modal.classList.add('pop-up--open');
		overlay.classList.add('db');
	})
});

const buttonsClose = document.querySelectorAll('.button--close');

buttonsClose.forEach(function (el) {
	el.addEventListener('click', function (evt) {
		const modalOpen = document.querySelector('.pop-up--open');
		modalOpen.classList.remove('pop-up--open');
		modalOpen.classList.add('pop-up--close');
		overlay.classList.remove('db');

	})
});

let starRating = document.querySelector(".active-stars");

if (starRating) {
	starRating.addEventListener("click", function (event) {
		let stars = event.currentTarget.childNodes;
		let rating = 0;
		stars.forEach((star) => star.classList.remove("fill-star"));

		for (let i = 0; i < stars.length; i++) {
			let element = stars[i];

			if (element.nodeName === "SPAN") {
				element.className = "fill-star";
				rating++;
			}

			if (element === event.target) {
				break;
			}
		}

		let inputField = document.getElementById('acceptance-form-rate');
		inputField.value = rating;
	});
}
