// Theme front-end entry point
import '../scss/main.scss';

import Splide from '@splidejs/splide';
import '@splidejs/splide/dist/css/splide-core.min.css';

document.addEventListener('DOMContentLoaded', () => {
  // ── Customer Reviews slider ──────────────────────────────────────────────────
  document.querySelectorAll('.section-reviews__splide').forEach((el) => {
    const slideCount = el.querySelectorAll('.splide__slide').length;
    new Splide(el, {
      type:       'loop',
      perPage:    3,
      perMove:    1,
      gap:        '1.5rem',
      arrows:     true,
      pagination: false,
      start:      Math.floor(Math.random() * slideCount),
      breakpoints: {
        1024: { perPage: 2, gap: '1.25rem' },
        640:  { perPage: 1, gap: '1rem'    },
      },
    }).mount();
  });

  // ── Section Work slider ───────────────────────────────────────────────────────
  document.querySelectorAll('.section-work__splide').forEach((el) => {
    const block = el.closest('.wp-block-globeiron-section-work');
    const tabs  = block ? Array.from(block.querySelectorAll('.section-work__tab')) : [];

    const splide = new Splide(el, {
      type:          'fade',
      rewind:        true,
      autoplay:      true,
      interval:      5000,
      pauseOnHover:  true,
      pauseOnFocus:  true,
      arrows:        false,
      pagination:    false,
    });

    tabs.forEach((tab, i) => {
      tab.addEventListener('click', () => splide.go(i));
    });

    splide.on('active', (slide) => {
      const idx = slide.index;
      tabs.forEach((tab, i) => tab.classList.toggle('is-active', i === idx));
    });

    splide.mount();
  });

  // ── Mobile nav slideout ───────────────────────────────────────────────────────
  const toggle   = document.querySelector('[data-nav-toggle]');
  const panel    = document.querySelector('[data-nav-panel]');
  const overlay  = document.querySelector('[data-nav-overlay]');
  const closeBtn = document.querySelector('[data-nav-close]');

  const isMobileNav = () => window.innerWidth < 1024;

  function openNav() {
    panel.classList.add('is-open');
    panel.setAttribute('aria-hidden', 'false');
    panel.removeAttribute('inert');
    overlay.classList.add('is-active');
    overlay.setAttribute('aria-hidden', 'false');
    toggle.setAttribute('aria-expanded', 'true');
    document.body.style.overflow = 'hidden';
  }

  function closeNav() {
    panel.classList.remove('is-open');
    panel.setAttribute('aria-hidden', 'true');
    if (isMobileNav()) panel.setAttribute('inert', '');
    overlay.classList.remove('is-active');
    overlay.setAttribute('aria-hidden', 'true');
    toggle.setAttribute('aria-expanded', 'false');
    document.body.style.overflow = '';
  }

  if (toggle && panel) {
    // HTML ships with inert for mobile-first default.
    // On desktop the panel is always visible inline, so remove both guards immediately.
    if (!isMobileNav()) {
      panel.removeAttribute('inert');
      panel.setAttribute('aria-hidden', 'false');
    }

    toggle.addEventListener('click', () => {
      panel.classList.contains('is-open') ? closeNav() : openNav();
    });

    if (closeBtn)  closeBtn.addEventListener('click', closeNav);
    if (overlay)   overlay.addEventListener('click', closeNav);

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && panel.classList.contains('is-open')) {
        closeNav();
        toggle.focus();
      }
    });

    window.addEventListener('resize', () => {
      if (window.innerWidth >= 1024) {
        // Switching to desktop: panel is always visible, remove mobile guards
        if (panel.classList.contains('is-open')) closeNav();
        panel.removeAttribute('inert');
        panel.setAttribute('aria-hidden', 'false');
      } else if (!panel.classList.contains('is-open')) {
        // Switching to mobile with panel closed: re-apply guards
        panel.setAttribute('inert', '');
        panel.setAttribute('aria-hidden', 'true');
      }
    });
  }

  const gsap = window.gsap;
  const ScrollTrigger = window.ScrollTrigger;
  const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  if (gsap && ScrollTrigger) {
    gsap.registerPlugin(ScrollTrigger);
  }

  function runWhenInView(trigger, play, options = {}) {
    if (!trigger) return;

    if (gsap && ScrollTrigger && !prefersReducedMotion) {
      ScrollTrigger.create({
        trigger,
        start: options.start || 'top 78%',
        once: true,
        onEnter: play,
      });
      return;
    }

    const io = new IntersectionObserver(
      ([entry]) => {
        if (entry.isIntersecting) {
          play();
          io.disconnect();
        }
      },
      { threshold: options.threshold ?? 0.15 }
    );
    io.observe(trigger);
  }

  function getCrosshairParts(svg) {
    return {
      svg,
      circle: svg?.querySelector('circle'),
      lines: svg ? svg.querySelectorAll('line') : [],
    };
  }

  function revealCrosshairStatic(svg) {
    const { circle, lines } = getCrosshairParts(svg);
    if (!svg) return;
    svg.style.opacity = '1';
    svg.style.transform = 'none';
    if (circle) circle.style.strokeDashoffset = '0';
    lines.forEach((line) => {
      line.style.strokeDashoffset = '0';
    });
  }

  function setCrosshairInitial(svg) {
    const { circle, lines } = getCrosshairParts(svg);
    gsap.set(svg, { autoAlpha: 0, scale: 0.76, transformOrigin: '50% 50%' });
    if (circle) gsap.set(circle, { strokeDasharray: 1, strokeDashoffset: 1 });
    if (lines.length) gsap.set(lines, { strokeDasharray: 1, strokeDashoffset: 1 });
  }

  function addCrosshairReveal(timeline, svg, at = 0) {
    const { circle, lines } = getCrosshairParts(svg);
    if (!svg) return;

    timeline
      .to(svg, { autoAlpha: 1, scale: 1, duration: 0.32, ease: 'back.out(1.8)' }, at);

    if (circle) {
      timeline.to(circle, { strokeDashoffset: 0, duration: 0.48, ease: 'power2.out' }, at);
    }

    if (lines.length) {
      timeline.to(lines, { strokeDashoffset: 0, duration: 0.28, ease: 'power2.out' }, at + 0.36);
    }
  }

  function animateScrollIndicator(indicator, trigger = indicator, options = {}) {
    if (!indicator) return;

    const top = indicator.querySelector('[class*="scroll-crosshair--top"]');
    const end = indicator.querySelector('[class*="scroll-crosshair--end"]');
    const line = indicator.querySelector('[class*="scroll-line"]');

    if (!top || !end || !line) return;

    const revealStatic = () => {
      revealCrosshairStatic(top);
      revealCrosshairStatic(end);
      line.style.setProperty('--gi-track-clip', '0%');
    };

    if (!gsap || prefersReducedMotion) {
      runWhenInView(trigger, revealStatic, options);
      return;
    }

    setCrosshairInitial(top);
    setCrosshairInitial(end);
    gsap.set(line, { '--gi-track-clip': '100%' });

    const timeline = gsap.timeline({ paused: true });
    addCrosshairReveal(timeline, top, 0);
    timeline
      .to(line, { '--gi-track-clip': '0%', duration: 0.8, ease: 'power2.out' }, 0.78);
    addCrosshairReveal(timeline, end, 1.58);

    runWhenInView(trigger, () => timeline.play(0), options);
  }

  // ── Core video block ornament: crosshair + animated vertical rail ──────────
  document.querySelectorAll('.wp-block-video').forEach((block) => {
    if (!block.querySelector('.wp-block-video__ornament')) {
      const ornament = document.createElement('div');
      ornament.className = 'wp-block-video__ornament';
      ornament.setAttribute('aria-hidden', 'true');
      ornament.innerHTML = `
        <svg class="wp-block-video__crosshair wp-block-video__crosshair--start" viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" focusable="false">
          <circle cx="14" cy="14" r="12" pathLength="1"></circle>
          <line x1="14" y1="7" x2="14" y2="21" pathLength="1"></line>
          <line x1="7" y1="14" x2="21" y2="14" pathLength="1"></line>
        </svg>
        <span class="wp-block-video__line"></span>
        <svg class="wp-block-video__crosshair wp-block-video__crosshair--end" viewBox="0 0 28 28" fill="none" stroke="currentColor" stroke-width="1.5" xmlns="http://www.w3.org/2000/svg" focusable="false">
          <circle cx="14" cy="14" r="12" pathLength="1"></circle>
          <line x1="14" y1="7" x2="14" y2="21" pathLength="1"></line>
          <line x1="7" y1="14" x2="21" y2="14" pathLength="1"></line>
        </svg>
      `;
      block.prepend(ornament);
    }

    const start = block.querySelector('.wp-block-video__crosshair--start');
    const end = block.querySelector('.wp-block-video__crosshair--end');
    const line = block.querySelector('.wp-block-video__line');

    if (!start || !end || !line) return;

    const revealStatic = () => {
      revealCrosshairStatic(start);
      revealCrosshairStatic(end);
      line.style.transform = 'scaleY(1)';
    };

    if (!gsap || prefersReducedMotion) {
      runWhenInView(block, revealStatic, { threshold: 0.35 });
      return;
    }

    setCrosshairInitial(start);
    setCrosshairInitial(end);
    gsap.set(line, { scaleY: 0, transformOrigin: 'top center' });

    const timeline = gsap.timeline({ paused: true });
    addCrosshairReveal(timeline, start, 0);
    timeline.to(line, { scaleY: 1, duration: 0.78, ease: 'power2.out' }, 0.58);
    addCrosshairReveal(timeline, end, 1.28);

    runWhenInView(block, () => timeline.play(0), { threshold: 0.35, start: 'top 78%' });
  });

  // ── Reviews header stars animation ─────────────────────────────────────────
  document.querySelectorAll('.section-reviews__stars').forEach((starsEl) => {
    const stars = Array.from(starsEl.querySelectorAll('.section-reviews__star'));
    if (!stars.length || prefersReducedMotion || !gsap) return;

    let entryDone = false;

    // Subtle cascade pulse — filled stars only
    function pulseFilled() {
      const filled = stars.filter(s => s.classList.contains('is-filled'));
      if (!filled.length) return;
      gsap.to(filled, {
        scale:    1.22,
        duration: 0.26,
        ease:     'sine.inOut',
        yoyo:     true,
        repeat:   1,
        stagger:  0.055,
      });
    }

    // Schedule next pulse at a random 6–8 s interval
    function schedulePulse() {
      const delay = 6000 + gsap.utils.random(0, 2000);
      setTimeout(() => { pulseFilled(); schedulePulse(); }, delay);
    }

    // Entry: stars pop in sequentially, then kick off the idle loop
    function entryAnimate() {
      if (entryDone) return;
      entryDone = true;
      gsap.to(stars, {
        opacity:    1,
        scale:      1,
        duration:   0.48,
        ease:       'back.out(1.6)',
        stagger:    0.09,
        onComplete: schedulePulse,
      });
    }

    // Hide stars before the animation fires so they don't flash in
    gsap.set(stars, { opacity: 0, scale: 0.45 });
    runWhenInView(starsEl, entryAnimate, { threshold: 0.6, start: 'top 80%' });
  });

  // ── Hero + features scroll indicators ──────────────────────────────────────
  animateScrollIndicator(
    document.querySelector('.hero-home__scroll-indicator'),
    document.querySelector('.hero-home__scroll-indicator'),
    { threshold: 0.1, start: 'top 92%' }
  );

  const featuresIndicator = document.querySelector('.section-features__scroll-indicator');
  animateScrollIndicator(
    featuresIndicator,
    document.querySelector('.wp-block-globeiron-section-services') ?? featuresIndicator,
    { threshold: 0, start: 'top 94%' }
  );

  document.querySelectorAll('.page-404__indicator').forEach((indicator) => {
    const crosshair = indicator.querySelector('.page-404__crosshair');
    const line = indicator.querySelector('.page-404__line');
    if (!crosshair || !line) return;

    const revealStatic = () => {
      revealCrosshairStatic(crosshair);
      line.style.setProperty('--gi-track-clip', '0%');
    };

    if (!gsap || prefersReducedMotion) {
      runWhenInView(indicator, revealStatic, { threshold: 0.2 });
      return;
    }

    setCrosshairInitial(crosshair);
    gsap.set(line, { '--gi-track-clip': '100%' });

    const timeline = gsap.timeline({ paused: true });
    addCrosshairReveal(timeline, crosshair, 0);
    timeline
      .to(line, { '--gi-track-clip': '0%', duration: 0.8, ease: 'power2.out' }, 0.58);

    runWhenInView(indicator, () => timeline.play(0), { threshold: 0.2, start: 'top 88%' });
  });

  // ── Interior hero collage: crosshair + dotted rail reveal ───────────────────
  document.querySelectorAll('.hero-interior--collage').forEach((hero) => {
    const ornaments = hero.querySelectorAll('.hero-interior__collage-ornament');
    const vertical  = hero.querySelector('.hero-interior__collage-line--vertical');
    const horizontal = hero.querySelector('.hero-interior__collage-line--horizontal');

    if (!ornaments.length || !vertical || !horizontal) return;

    const revealStatic = () => {
      ornaments.forEach(revealCrosshairStatic);
      [vertical, horizontal].forEach((el) => {
        el.style.opacity = '1';
        el.style.transform = 'none';
      });
      hero.classList.add('is-visible');
    };

    if (prefersReducedMotion || !gsap) {
      runWhenInView(hero, revealStatic, { threshold: 0.35 });
      return;
    }

    const topCrosshair = hero.querySelector('.hero-interior__collage-ornament--top');
    const bottomCrosshair = hero.querySelector('.hero-interior__collage-ornament--bottom');
    const leftCrosshair = hero.querySelector('.hero-interior__collage-ornament--left');
    const rightCrosshair = hero.querySelector('.hero-interior__collage-ornament--right');

    ornaments.forEach(setCrosshairInitial);
    gsap.set(vertical, { autoAlpha: 1, scaleY: 0 });
    gsap.set(horizontal, { autoAlpha: 1, scaleX: 0 });

    const timeline = gsap.timeline({
      paused: true,
      defaults: { ease: 'power2.out' },
      onStart: () => hero.classList.add('is-visible'),
    });

    addCrosshairReveal(timeline, topCrosshair, 0);
    timeline.to(vertical, { scaleY: 1, duration: 0.9, ease: 'power1.inOut' }, 0.58);
    addCrosshairReveal(timeline, bottomCrosshair, 1.28);
    addCrosshairReveal(timeline, leftCrosshair, 1.44);
    timeline.to(horizontal, { scaleX: 1, duration: 0.72, ease: 'power1.inOut' }, 1.72);
    addCrosshairReveal(timeline, rightCrosshair, 2.28);

    runWhenInView(hero, () => timeline.play(0), { threshold: 0.35, start: 'top 68%' });
  });

  function animateSideOrnaments(section, ornamentSelector, crosshairSelector, lineFirst = false) {
    if (!section) return;

    const ornaments = section.querySelectorAll(ornamentSelector);
    const crosshairs = section.querySelectorAll(crosshairSelector);
    if (!ornaments.length || !crosshairs.length) return;

    const revealStatic = () => {
      ornaments.forEach((ornament) => ornament.style.setProperty('--gi-ornament-line-clip', '0%'));
      crosshairs.forEach(revealCrosshairStatic);
    };

    if (!gsap || prefersReducedMotion) {
      runWhenInView(section, revealStatic);
      return;
    }

    ornaments.forEach((ornament) => ornament.style.setProperty('--gi-ornament-line-clip', '100%'));
    crosshairs.forEach(setCrosshairInitial);

    const timeline = gsap.timeline({ paused: true });
    const crosshairAt = lineFirst ? 0.86 : 0.08;
    const lineAt = lineFirst ? 0.08 : 0.72;

    crosshairs.forEach((crosshair) => addCrosshairReveal(timeline, crosshair, crosshairAt));
    timeline.to(ornaments, {
      '--gi-ornament-line-clip': '0%',
      duration: 0.9,
      ease: 'power2.out',
    }, lineAt);

    runWhenInView(section, () => timeline.play(0));
  }

  // ── Side crosshair ornaments ───────────────────────────────────────────────
  animateSideOrnaments(
    document.querySelector('.wp-block-globeiron-section-features'),
    '.section-features__ornament',
    '.section-features__ornament-crosshair'
  );
  animateSideOrnaments(
    document.querySelector('.wp-block-globeiron-section-services'),
    '.section-services__ornament',
    '.section-services__crosshair',
    true
  );
  document.querySelectorAll('.wp-block-globeiron-service-hubs').forEach((section) => {
    animateSideOrnaments(
      section,
      '.section-service-hubs__ornament',
      '.section-service-hubs__ornament-crosshair',
      true
    );
  });

  function animateMapRail(section) {
    const top = section.querySelector('.section-contact-map__rail-crosshair--top');
    const bottom = section.querySelector('.section-contact-map__rail-crosshair--bottom');
    const line = section.querySelector('.section-contact-map__rail-line');
    if (!top || !bottom || !line) return;

    const revealStatic = () => {
      [top, bottom].forEach((crosshair) => {
        crosshair.style.opacity = '1';
        crosshair.style.transform = 'none';
      });
      line.style.transform = 'scaleY(1)';
    };

    if (!gsap || prefersReducedMotion) {
      runWhenInView(section, revealStatic);
      return;
    }

    gsap.set([top, bottom], { autoAlpha: 0, scale: 0.5, transformOrigin: '50% 50%' });
    gsap.set(line, { scaleY: 0, transformOrigin: 'top center' });

    const timeline = gsap.timeline({ paused: true });
    timeline
      .to(top, { autoAlpha: 1, scale: 1, duration: 0.38, ease: 'back.out(1.8)' }, 0.2)
      .to(line, { scaleY: 1, duration: 0.8, ease: 'power2.out' }, 0.58)
      .to(bottom, { autoAlpha: 1, scale: 1, duration: 0.38, ease: 'back.out(1.8)' }, 1.36);

    runWhenInView(section, () => timeline.play(0));
  }

  function animateBorderColumns(section) {
    const edges = {
      top: section.querySelector('.section-border-columns__edge--top'),
      right: section.querySelector('.section-border-columns__edge--right'),
      bottom: section.querySelector('.section-border-columns__edge--bottom'),
      left: section.querySelector('.section-border-columns__edge--left'),
    };
    const corners = {
      tl: section.querySelector('.section-border-columns__corner--tl .section-border-columns__crosshair'),
      tr: section.querySelector('.section-border-columns__corner--tr .section-border-columns__crosshair'),
      br: section.querySelector('.section-border-columns__corner--br .section-border-columns__crosshair'),
      bl: section.querySelector('.section-border-columns__corner--bl .section-border-columns__crosshair'),
    };

    if (!edges.top || !edges.right || !edges.bottom || !edges.left) return;

    const revealStatic = () => {
      edges.top.style.clipPath = 'inset(0% 0% 0% 0%)';
      edges.right.style.clipPath = 'inset(0% 0% 0% 0%)';
      edges.bottom.style.clipPath = 'inset(0% 0% 0% 0%)';
      edges.left.style.clipPath = 'inset(0% 0% 0% 0%)';
      Object.values(corners).forEach(revealCrosshairStatic);
    };

    if (!gsap || prefersReducedMotion) {
      runWhenInView(section, revealStatic);
      return;
    }

    gsap.set(edges.top, { clipPath: 'inset(0% 100% 0% 0%)' });
    gsap.set(edges.right, { clipPath: 'inset(0% 0% 100% 0%)' });
    gsap.set(edges.bottom, { clipPath: 'inset(0% 0% 0% 100%)' });
    gsap.set(edges.left, { clipPath: 'inset(100% 0% 0% 0%)' });
    Object.values(corners).filter(Boolean).forEach(setCrosshairInitial);

    const timeline = gsap.timeline({ paused: true });
    timeline
      .to(edges.top, { clipPath: 'inset(0% 0% 0% 0%)', duration: 0.6, ease: 'power1.inOut' }, 0)
      .to(edges.right, { clipPath: 'inset(0% 0% 0% 0%)', duration: 0.6, ease: 'power1.inOut' }, 0.4)
      .to(edges.bottom, { clipPath: 'inset(0% 0% 0% 0%)', duration: 0.6, ease: 'power1.inOut' }, 0.8)
      .to(edges.left, { clipPath: 'inset(0% 0% 0% 0%)', duration: 0.6, ease: 'power1.inOut' }, 1.2);

    addCrosshairReveal(timeline, corners.tr, 0.54);
    addCrosshairReveal(timeline, corners.br, 0.94);
    addCrosshairReveal(timeline, corners.bl, 1.34);
    addCrosshairReveal(timeline, corners.tl, 1.74);

    runWhenInView(section, () => timeline.play(0));
  }

  // ── Align features ornaments with the grid's top edge ───────────────────────
  // Sets --features-ornament-top so the crosshair centres on the grid's first row
  // of horizontal dash lines. Re-runs on resize to handle reflow.
  function positionFeaturesOrnaments() {
    const section = document.querySelector('.wp-block-globeiron-section-features');
    const inner   = section?.querySelector('.section-features__inner');
    const grid    = inner?.querySelector('.section-features__grid');
    if (!section || !inner || !grid) return;
    // measure grid top relative to inner (the ornaments' containing block)
    const offset = grid.getBoundingClientRect().top - inner.getBoundingClientRect().top;
    // subtract half the crosshair height (14px) to vertically centre it at the grid top
    section.style.setProperty('--features-ornament-top', `${offset - 14}px`);
  }

  positionFeaturesOrnaments();
  window.addEventListener('resize', positionFeaturesOrnaments);

  // ── Section Map: variant dropdown + animation observer ───────────────────────
  document.querySelectorAll('[data-map-select]').forEach((select) => {
    const section = select.closest('.wp-block-globeiron-section-contact-map');
    if (!section) return;

    const panels = section.querySelectorAll('[data-map-panel]');

    function activatePanel(value) {
      panels.forEach((panel) => {
        const isTarget = panel.dataset.mapPanel === value;
        panel.classList.toggle('is-active', isTarget);
      });
    }

    select.addEventListener('change', () => activatePanel(select.value));
  });

  document.querySelectorAll('.wp-block-globeiron-section-contact-map').forEach(animateMapRail);
  document.querySelectorAll('.wp-block-globeiron-section-border-columns').forEach(animateBorderColumns);

  // ── Project header ornament + snapshot positioning ──────────────────────────
  function positionProjectOrnaments() {
    document.querySelectorAll('.project-header').forEach((block) => {
      const headline = block.querySelector('.project-header__headline');
      const hero     = block.querySelector('.project-header__hero');
      const backLink = block.querySelector('.project-header__back');
      const snapshot = block.querySelector('.project-header__snapshot');
      if (!headline || !hero) return;

      const heroInner   = block.querySelector('.project-header__hero-inner');
      const blockRect   = block.getBoundingClientRect();
      const h1Rect      = headline.getBoundingClientRect();
      const heroRect    = hero.getBoundingClientRect();
      const CROSSHAIR   = 28;

      // Snapshot: start just below the back link row
      if (snapshot && backLink) {
        const backBottom = backLink.getBoundingClientRect().bottom - blockRect.top;
        block.style.setProperty('--snapshot-top', backBottom + 'px');
      }

      // Ornament horizontal: flush with the container's left edge
      const ornamentLeft = heroInner
        ? Math.max(0, heroInner.getBoundingClientRect().left - blockRect.left)
        : 0;

      // Ornament vertical top: top crosshair at H1 top
      const ornamentTop = h1Rect.top - blockRect.top;

      // Ornament vertical bottom: bottom crosshair at top of first description element
      const descEl         = block.querySelector('.project-body__description > *:first-child')
                          || block.querySelector('.project-body__description');
      const descTop        = descEl ? descEl.getBoundingClientRect().top - blockRect.top : blockRect.height;
      const ornamentBottom = Math.max(0, blockRect.height - descTop - CROSSHAIR);

      // Line color split at hero / project-body boundary
      const lineStart   = ornamentTop  + CROSSHAIR;
      const lineEnd     = blockRect.height - ornamentBottom - CROSSHAIR;
      const heroBottom  = heroRect.bottom - blockRect.top;
      const whiteHeight = Math.max(0, heroBottom - lineStart);
      const blueHeight  = Math.max(0, lineEnd    - heroBottom);

      block.style.setProperty('--ornament-left',     ornamentLeft   + 'px');
      block.style.setProperty('--ornament-top',      ornamentTop    + 'px');
      block.style.setProperty('--ornament-bottom',   ornamentBottom + 'px');
      block.style.setProperty('--line-white-height', whiteHeight    + 'px');
      block.style.setProperty('--line-blue-height',  blueHeight     + 'px');
    });
  }

  positionProjectOrnaments();
  let _ornamentRaf;
  window.addEventListener('resize', () => {
    cancelAnimationFrame(_ornamentRaf);
    _ornamentRaf = requestAnimationFrame(positionProjectOrnaments);
  });

  // ── Project Details — Before/After pair navigation ───────────────────────────
  document.querySelectorAll('[data-ba-container]').forEach((container) => {
    const media   = container.closest('.project-details__media');
    const prevBtn = media?.querySelector('[data-ba-prev]');
    const nextBtn = media?.querySelector('[data-ba-next]');
    const pairs   = Array.from(container.querySelectorAll('[data-ba-pair]'));
    if (!pairs.length) return;

    const ornament = container.querySelector('.project-details__ornament');
    const ornCh1   = ornament?.querySelector('.project-details__ornament-ch:first-child svg');
    const ornCh2   = ornament?.querySelector('.project-details__ornament-ch:last-child svg');
    const hline    = ornament?.querySelector('.project-details__ornament-hline');
    const vline    = ornament?.querySelector('.project-details__ornament-vline');

    let current = 0;
    let busy    = false;

    // ── Ornament: initial state + scroll-triggered reveal ──────────────────
    if (gsap && ornament && !prefersReducedMotion) {
      if (ornCh1) setCrosshairInitial(ornCh1);
      if (ornCh2) setCrosshairInitial(ornCh2);
      if (hline)  gsap.set(hline, { scaleX: 0, transformOrigin: '0% 50%' });
      if (vline)  gsap.set(vline, { scaleY: 0, transformOrigin: '50% 0%' });

      runWhenInView(container, () => {
        const tl = gsap.timeline();
        if (ornCh1) addCrosshairReveal(tl, ornCh1, 0);
        if (hline)  tl.to(hline, { scaleX: 1, duration: 0.4, ease: 'power2.out' }, 0.44);
        if (vline)  tl.to(vline, { scaleY: 1, duration: 0.5, ease: 'power2.out' }, 0.72);
        if (ornCh2) addCrosshairReveal(tl, ornCh2, 1.1);
      });
    }

    // ── Ornament rescan on pair change (lines only, no crosshair re-pop) ───
    function rescanOrnament() {
      if (!gsap || !ornament || prefersReducedMotion) return;
      const tl = gsap.timeline({ delay: 0.08 });
      if (hline) {
        gsap.set(hline, { scaleX: 0, transformOrigin: '0% 50%' });
        tl.to(hline, { scaleX: 1, duration: 0.32, ease: 'power2.out' }, 0);
      }
      if (vline) {
        gsap.set(vline, { scaleY: 0, transformOrigin: '50% 0%' });
        tl.to(vline, { scaleY: 1, duration: 0.38, ease: 'power2.out' }, 0.18);
      }
    }

    // ── Pair transition ────────────────────────────────────────────────────
    function goTo(index, dir = 1) {
      if (busy) return;
      const next = (index + pairs.length) % pairs.length;
      if (next === current) return;
      busy = true;

      const outPair = pairs[current];
      const inPair  = pairs[next];
      current = next;

      outPair.setAttribute('aria-hidden', 'true');
      inPair.setAttribute('aria-hidden', 'false');

      if (gsap && !prefersReducedMotion) {
        const yOffset = 28 * dir;

        // Prepare incoming images before making the pair visible
        const wBefore = inPair.querySelector('.project-details__img-wrap--before');
        const wAfter  = inPair.querySelector('.project-details__img-wrap--after');
        const targets = [wBefore, wAfter].filter(Boolean);
        gsap.set(targets, { opacity: 0, y: yOffset });

        // Fade out current pair
        gsap.to(outPair, {
          opacity: 0,
          duration: 0.22,
          ease: 'power1.in',
          onComplete: () => {
            outPair.classList.remove('is-active');
            gsap.set(outPair, { clearProps: 'opacity' });
          },
        });

        // Show incoming pair, stagger-animate the two image wraps
        inPair.classList.add('is-active');
        gsap.to(targets, {
          opacity: 1,
          y: 0,
          duration: 0.44,
          ease: 'power2.out',
          stagger: 0.1,
          delay: 0.14,
          onComplete: () => {
            gsap.set(targets, { clearProps: 'opacity,y' });
            busy = false;
          },
        });

        rescanOrnament();
      } else {
        outPair.classList.remove('is-active');
        inPair.classList.add('is-active');
        busy = false;
      }
    }

    if (prevBtn) prevBtn.addEventListener('click', () => goTo(current - 1, -1));
    if (nextBtn) nextBtn.addEventListener('click', () => goTo(current + 1,  1));
  });

  // ── Mobile submenu accordion ─────────────────────────────────────────────────
  // Inject a standalone toggle <button> after each parent link so the <a> can
  // navigate to its href normally. The toggle button handles expand/collapse.
  // On desktop, hover via CSS handles dropdowns — the button is display:none.
  document.querySelectorAll('.nav > .menu-item-has-children').forEach((item) => {
    const link = item.querySelector(':scope > a');
    if (!link) return;

    const btn = document.createElement('button');
    btn.className = 'nav__dropdown-toggle';
    btn.type = 'button';
    btn.setAttribute('aria-expanded', 'false');
    btn.setAttribute('aria-label', 'Toggle submenu');
    btn.innerHTML = `<svg viewBox="0 0 10 10" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
      <polyline points="2,3 5,7 8,3"/>
    </svg>`;

    link.after(btn);

    const toggle = (forceClose = false) => {
      if (window.innerWidth >= 1024) return;
      const isOpen = forceClose ? false : !item.classList.contains('is-open');
      item.classList.toggle('is-open', isOpen);
      btn.setAttribute('aria-expanded', String(isOpen));

      // Collapse siblings
      if (isOpen) {
        item.parentElement
          .querySelectorAll(':scope > .menu-item-has-children.is-open')
          .forEach((sibling) => {
            if (sibling !== item) {
              sibling.classList.remove('is-open');
              sibling.querySelector('.nav__dropdown-toggle')?.setAttribute('aria-expanded', 'false');
            }
          });
      }
    };

    btn.addEventListener('click', () => toggle());
  });

  // ── Team grid: crosshair + dotted-line ornament animation ───────────────────
  document.querySelectorAll('.wp-block-globeiron-section-team-grid').forEach((section) => {
    const headerCh   = section.querySelector('.section-team-grid__header-crosshair svg');
    const headerLine = section.querySelector('.section-team-grid__header-line');
    const cards      = Array.from(section.querySelectorAll('.team-card'));

    const revealStatic = () => {
      if (headerCh)   revealCrosshairStatic(headerCh);
      if (headerLine) headerLine.style.transform = 'scaleX(1)';
      cards.forEach((card) => {
        const ch = card.querySelector('.team-card__crosshair svg');
        const hl = card.querySelector('.team-card__ornament-hline');
        const vl = card.querySelector('.team-card__ornament-vline');
        if (ch) revealCrosshairStatic(ch);
        if (hl) hl.style.transform = 'scaleX(1)';
        if (vl) vl.style.transform = 'scaleY(1)';
      });
    };

    if (!gsap || prefersReducedMotion) {
      runWhenInView(section, revealStatic);
      return;
    }

    // Set initial hidden states
    if (headerCh)   setCrosshairInitial(headerCh);
    if (headerLine) gsap.set(headerLine, { scaleX: 0, transformOrigin: 'left center' });

    cards.forEach((card) => {
      const ch = card.querySelector('.team-card__crosshair svg');
      const hl = card.querySelector('.team-card__ornament-hline');
      const vl = card.querySelector('.team-card__ornament-vline');
      if (ch) setCrosshairInitial(ch);
      if (hl) gsap.set(hl, { scaleX: 0, transformOrigin: 'left center' });
      if (vl) gsap.set(vl, { scaleY: 0, transformOrigin: 'top center' });
    });

    const tl = gsap.timeline({ paused: true });

    // 1. Header ornament
    if (headerCh)   addCrosshairReveal(tl, headerCh, 0);
    if (headerLine) tl.to(headerLine, { scaleX: 1, duration: 0.85, ease: 'power2.out' }, 0.28);

    // 2. Cards staggered left → right
    cards.forEach((card, i) => {
      const ch = card.querySelector('.team-card__crosshair svg');
      const hl = card.querySelector('.team-card__ornament-hline');
      const vl = card.querySelector('.team-card__ornament-vline');
      const at = 0.55 + i * 0.18;

      if (ch) addCrosshairReveal(tl, ch, at);
      if (hl) tl.to(hl, { scaleX: 1, duration: 0.52, ease: 'power2.out' }, at + 0.3);
      if (vl) tl.to(vl, { scaleY: 1, duration: 0.68, ease: 'power2.out' }, at + 0.44);
    });

    runWhenInView(section, () => tl.play(0), { start: 'top 75%' });
  });

  // ── Team member bio modals ────────────────────────────────────────────────────
  document.querySelectorAll('[data-team-card]').forEach((card) => {
    card.addEventListener('click', () => {
      const modal = document.getElementById(card.dataset.teamCard);
      if (modal) modal.showModal();
    });
  });

  document.querySelectorAll('.team-modal').forEach((modal) => {
    modal.querySelector('.team-modal__close')?.addEventListener('click', () => modal.close());

    // Click on the backdrop (directly on <dialog>, outside the panel) closes it
    modal.addEventListener('click', (e) => {
      if (e.target === modal) modal.close();
    });
  });
});
