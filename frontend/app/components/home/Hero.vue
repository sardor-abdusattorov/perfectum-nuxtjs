<script setup lang="ts">
const block = useBlock('home', 'hero')

const slides = computed(() => published(block.value.slides))
const buttons = (slide: Record<string, any>) => published(slide.buttons)

const t = useT()
const slider = useTemplateRef('slider')
const still = import.meta.client && window.matchMedia('(prefers-reduced-motion: reduce)').matches

useSlider(slider, {
  slidesPerView: 1,
  loop: slides.value.length > 1,
  speed: 700,
  watchOverflow: true,
  autoHeight: false,
  autoplay: still ? false : { delay: 6000, disableOnInteraction: false },
  pagination: {
    el: '.hero__pagination',
    clickable: true,
    bulletElement: 'button',
  },
  a11y: {
    prevSlideMessage: t('common.prev'),
    nextSlideMessage: t('common.next'),
  },
}, () => slides.value)
</script>

<template>
  <section v-if="slides.length" class="hero">
    <div ref="slider" class="hero__slider swiper">
      <div class="swiper-wrapper">
        <div v-for="(slide, index) in slides" :key="index" class="swiper-slide">
          <div class="container">
            <div class="hero__row">
              <div class="hero__content">
                <div class="hero__texts">
                  <div v-if="slide.description" class="hero__description">
                    <p>{{ slide.description }}</p>
                  </div>
                  <h1
                    class="hero__title"
                    v-html="rich(slide.title, { accent: 'hero__title-red', outline: 'hero__title-outline' })"
                  ></h1>
                </div>

                <div v-if="slide.show_aside !== false" class="hero__mobile">
                  <div v-if="slide.show_gauge !== false" class="hero__mobile-image">
                    <HomeHeroGauge :uid="`m-${index}`" :value="slide.gauge_value" />
                  </div>
                  <div class="hero__mobile-text">
                    <p v-html="rich(slide.lead)"></p>
                  </div>
                </div>

                <div v-if="buttons(slide).length" class="hero__actions">
                  <LayoutCardLink
                    v-for="(button, i) in buttons(slide)"
                    :key="i"
                    :url="button.url"
                    :class="['btn', `btn_${button.style ?? 'primary'}`]"
                  >{{ button.label }}
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="14" viewBox="0 0 18 14" fill="none">
                      <path
                        d="M16 6.70709L16.7071 5.99999L17.4142 6.70709L16.7071 7.4142L16 6.70709ZM1 7.70709C0.447715 7.70709 0 7.25938 0 6.70709C0 6.15481 0.447715 5.70709 1 5.70709V6.70709V7.70709ZM10 0.707092L10.7071 -1.44839e-05L16.7071 5.99999L16 6.70709L15.2929 7.4142L9.29289 1.4142L10 0.707092ZM16 6.70709L16.7071 7.4142L10.7071 13.4142L10 12.7071L9.29289 12L15.2929 5.99999L16 6.70709ZM16 6.70709V7.70709H1V6.70709V5.70709H16V6.70709Z"
                        fill="currentColor" />
                    </svg>
                  </LayoutCardLink>
                </div>

                <HomeHeroStores v-if="slide.show_aside !== false" class="hero__apps_mobile" />
              </div>

              <div class="hero__visual">
                <div class="hero__phone">
                  <img class="hero__image" :src="slide.image ?? '/images/hero-image-2.png'" :alt="slide.image_alt ?? ''" />
                  <HomeHeroGauge v-if="slide.show_gauge !== false" :uid="`hd-${index}`" :value="slide.gauge_value" class="hero__gauge_desktop" />
                </div>
              </div>

              <div v-if="slide.show_aside !== false" class="hero__aside">
                <p v-html="rich(slide.lead)"></p>

                <HomeHeroStores />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="hero__pagination swiper-pagination"></div>
    </div>
  </section>
</template>
