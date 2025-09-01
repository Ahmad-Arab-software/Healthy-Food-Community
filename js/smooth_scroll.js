
function smoothScroll(target, duration) {
    let targetPosition = target.getBoundingClientRect().top;
    let startPosition = window.pageYOffset;
    let startTime = null;

    function animationScroll(currentTime) {
        if (startTime === null) startTime = currentTime;
        let timeElapsed = currentTime - startTime;
        let run = ease(timeElapsed, startPosition, targetPosition, duration);
        window.scrollTo(0, run);
        if (timeElapsed < duration) requestAnimationFrame(animationScroll);
    }

    // Easing function for smooth animation
    function ease(t, b, c, d) {
        t /= d / 2;
        if (t < 1) return (c / 2) * t * t + b;
        t--;
        return (-c / 2) * (t * (t - 2) - 1) + b;
    }

    requestAnimationFrame(animationScroll);
}

document
    .getElementById("scroll-button")
    ?.addEventListener("click", function () {
        const section = document.getElementById("what-you-can-cook");
        smoothScroll(section, 2000); // 2000ms = 2 seconds for a slow smooth scroll
    });

document.addEventListener("DOMContentLoaded", () => {
    const recipeCards = document.querySelectorAll(".recipe-card");

    const observerOptions = {
        threshold: 0.1, // Wanneer 10% van de kaart zichtbaar is, wordt de animatie geactiveerd
    };

    const observer = new IntersectionObserver((entries, observer) => {
        entries.forEach((entry) => {
            if (entry.isIntersecting) {
                entry.target.classList.add("fade-in-visible");
                observer.unobserve(entry.target); // Stop met observeren zodra de animatie is toegepast
            }
        });
    }, observerOptions);

    recipeCards.forEach((card) => {
        card.classList.add("fade-in"); // Voeg de basis fade-in class toe aan alle kaarten
        observer.observe(card); // Begin met observeren
    });
});