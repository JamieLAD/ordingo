document.addEventListener('DOMContentLoaded', () => {

   const featureItems = document.querySelectorAll('.features-item');
   const featureCards = document.querySelectorAll('.features-card');
   const featuresScreen = document.querySelector('.features-screen');

   if (window.innerWidth > 475) {
      function hideCards (cards) {
         cards.forEach((card) => {
            card.classList.remove('show-card');
         });
      };
   
      function showCurrCard (card) {
         const activeCard = document.querySelector(`[data-features-card="${card}"]`);
         const activeItem = document.querySelector(`[data-features-link="${card}"]`);
   
         featureItems.forEach((featureItem) => {
            featureItem.classList.remove('active');
         });
   
         activeCard.classList.add('show-card');
         activeItem.classList.add('active');
         featuresScreen.style.backgroundImage = `url(../assets/img/${card}.jpg)`;
      }
   
      featureItems.forEach((item) => {
         item.addEventListener('click', e => {
            const currItem = e.currentTarget.dataset.featuresLink;
            hideCards(featureCards);
            showCurrCard(currItem)
         });
      });
   
      hideCards(featureCards);
      showCurrCard('feature-1');
   } else {
      featureItems.forEach(item => {
         item.addEventListener('click', e => {
            currItem = e.currentTarget;
            featureItems.forEach(i => {
               i.classList.remove('accordion');
            });
            currItem.classList.add('accordion');
         });
      });
   }

   const faqItems = document.querySelectorAll('.faq-item');

   faqItems.forEach(item => {
      item.addEventListener('click', e => {
         currItem = e.currentTarget;
         if (currItem.classList.contains('active')) {
            currItem.classList.remove('active');
         } else {
            currItem.classList.add('active');
         }
      });
   });

   const menuToggle = document.querySelector('.menu-toggle');
   const nav = document.querySelector('.main-nav');
   const overlay = document.querySelector('.overlay');

   menuToggle.addEventListener('click', e => {
      if (menuToggle.classList.contains('open')) {
         menuToggle.classList.remove('open');
         nav.classList.remove('open');
         overlay.classList.remove('open');
      } else {
         menuToggle.classList.add('open');
         nav.classList.add('open');
         overlay.classList.add('open');
      }
   });

   const featuresOffset = document.querySelector('.features').offsetTop;
   const header = document.querySelector('.header');
   
   if (document.offsetTop >= featuresOffset) {

      document.addEventListener('scroll', e => {
         if (document.offsetTop < featuresOffset) {
            header.classList.add('scrolled');
         } else {

         }
      });

   }

  (function(){

   var doc = document.documentElement;
   var w = window;
 
   var prevScroll = w.scrollY || doc.scrollTop;
   var curScroll;
   var direction = 0;
   var prevDirection = 0;
 
   var header = document.querySelector('.header');
 
   var checkScroll = function() {
 
     /*
     ** Find the direction of scroll
     ** 0 - initial, 1 - up, 2 - down
     */
 
     curScroll = w.scrollY || doc.scrollTop;
     if (curScroll > prevScroll) { 
       //scrolled up
       direction = 2;
     }
     else if (curScroll < prevScroll) { 
       //scrolled down
       direction = 1;
     }
 
     if (direction !== prevDirection) {
       toggleHeader(direction, curScroll);
     }
 
     prevScroll = curScroll;
   };
 
   var toggleHeader = function(direction, curScroll) {
     if (direction === 2 && curScroll > 184) { 
 
       //replace 52 with the height of your header in px
 
       header.classList.add('hide');
       prevDirection = direction;
     }
     else if (direction === 1) {
       header.classList.remove('hide');
       prevDirection = direction;
     }
   };
 
   window.addEventListener('scroll', checkScroll);
 
 })();

    // Select all links with hashes
$('a[href*="#"]')
// Remove links that don't actually link to anything
.not('[href="#"]')
.not('[href="#0"]')
.click(function(event) {
  // On-page links
  if (
    location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
    && 
    location.hostname == this.hostname
  ) {
    // Figure out element to scroll to
    var target = $(this.hash);
    target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
    // Does a scroll target exist?
    if (target.length) {
      // Only prevent default if animation is actually gonna happen
      event.preventDefault();
      $('html, body').animate({
        scrollTop: target.offset().top
      }, 1000, function() {
        // Callback after animation
        // Must change focus!
        var $target = $(target);
        $target.focus();
        if ($target.is(":focus")) { // Checking if the target was focused
          return false;
        } else {
          $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
          $target.focus(); // Set focus again
        };
      });
    }
  }
});

}, false);