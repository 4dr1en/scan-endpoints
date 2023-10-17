import { gsap } from "gsap";
import { ScrollTrigger } from "gsap/ScrollTrigger";
import { Observer } from "gsap/Observer";
import { set } from "date-fns";

gsap.registerPlugin(ScrollTrigger, Observer);

addEventListener('DOMContentLoaded', () => {
	const $title = document.getElementById('animated-title');
	const text = $title.textContent;
	$title.innerHTML = splitText($title.textContent);

	$title.querySelectorAll('span').forEach((letter, index) => {
		gsap.from(letter, {
			opacity: 0,
			y: '-100%',
			duration: 0.4,
			delay: (index * 0.5 + Math.random() * 2)/10,
			ease: 'power2.out',
		});
	});
	setTimeout(() => {
		console.log('test');
		console.log($title);
		$title.textContent = text;
	}, (text.length * 1000 * 0.5 + 2000)/10);

	// Homepage Hero Animation
	let tl = gsap.timeline({
		scrollTrigger: {
			trigger: '.homepage-hero__wrapper',
			start: 'top top',
			end: 'bottom top',
			scrub: 1,
			anticipatePin: 1,
			pin: true,
			pinSpacing:false,
		},
	});

	tl.to(document.getElementById('animated-title'), {
		opacity: 0,
		scale: 10,
		y: '-10%',
		ease: 'none',
	});

	const $hero = document.querySelector('.homepage-hero__wrapper');

	Observer.create({
		selector: $hero,
		threshold: 0.5,
		onMove: (entry) => {
			// get position of the mouse relative to the center of the hero
			let x = (entry.x - $hero.offsetWidth / 2);
			let y = (entry.y - $hero.offsetHeight / 2);
			let px = x / $hero.offsetWidth * 2 * 100;
			let py = y / $hero.offsetHeight * 2 * 100;

			gsap.to(document.getElementById('animated-title'), {
				x: px * 0.3,
				y: py * 0.3,
				rotateX: Math.min(Math.max(py * 0.2, -40), 40), // limits rotation between -40 to 40 degrees
				rotateY: Math.min(Math.max(px * 0.2, -40), 40), // limits rotation between -40 to 40 degrees
				perspective: 1000,
				ease: 'power2.out',
				duration: 0.5
			});
		},

		OnLeave: (entry) => {
			// reset the title position
			gsap.to(document.getElementById('animated-title'), {
				x: 0,
				y: 0,
				rotateX: 0,
				rotateY: 0,
				perspective: 1000,
				skewX: 0,
				skewY: 0,
				scale: 1,
				ease: 'power2.out',
				duration: 0.5,
				delay: 0.1,
			});
		}
	});

	// Homepage Features Animation
	const features = document.querySelectorAll('.homepage-feature');
	
	features.forEach((feature) => {
		let tl = gsap.timeline({
			scrollTrigger: {
				trigger: feature,
				start: 'top center',
				end: 'bottom center',
				scrub: 1,
			},
		});

		tl.from(feature.querySelector('.homepage-feature__image'), {
			x: '-10vw',
			opacity: 0,
			ease: "sine.out",
		})
		.from(feature.querySelector('hgroup'), {
			x: '10vw',
			opacity: 0,
			ease: "sine.out",
		}, "-=0.5")
		.to(feature.querySelector('.homepage-feature__image'), {
			x: '10vw',
			opacity: 0,
			ease: "sine.in",
		})
		.to(feature.querySelector('hgroup'), {
			x: '-10vw',
			opacity: 0,
			ease: "sine.in",
		}, "-=0.5");
	});

	// Homepage CTA Animation
	let cta = gsap.timeline({
		scrollTrigger: {
			trigger: '.homepage-cta',
			start: '+=10% center',
			end: 'bottom center',
			toggleActions: 'play none none reverse',
		},
	});

	cta.from('.homepage-cta__wrapper', {
		opacity: 0,
		y: '5vh',
		ease: "sine.out",
		delay: 0.5,
	})
});


function splitText(text) {
	let splitText = text.split('');
	let result = '';

	splitText.forEach((letter) => {
		result += `<span>${letter}</span>`;
	});

	return result;
}

