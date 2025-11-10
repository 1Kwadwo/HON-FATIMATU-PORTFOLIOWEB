import './bootstrap';

/**
 * Scroll Animation with Intersection Observer
 * 
 * Implements fade-in animations for elements with .fade-in class
 * - Opacity: 0 → 1
 * - Transform: translateY(20px) → translateY(0)
 * - Duration: 600ms
 * - Easing: ease-out
 */

document.addEventListener('DOMContentLoaded', function () {
    // Configuration for Intersection Observer
    const observerOptions = {
        root: null, // Use viewport as root
        rootMargin: '0px 0px -50px 0px', // Trigger slightly before element enters viewport
        threshold: 0.1 // Trigger when 10% of element is visible
    };

    // Callback function when elements intersect
    const observerCallback = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                // Add the 'visible' class to trigger animation
                entry.target.classList.add('visible');

                // Optional: Stop observing after animation (one-time animation)
                observer.unobserve(entry.target);
            }
        });
    };

    // Create the Intersection Observer
    const observer = new IntersectionObserver(observerCallback, observerOptions);

    // Observe all elements with .fade-in class
    const fadeInElements = document.querySelectorAll('.fade-in');
    fadeInElements.forEach(element => {
        observer.observe(element);
    });
});
