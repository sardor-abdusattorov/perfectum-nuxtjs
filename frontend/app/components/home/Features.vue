<script setup lang="ts">
const block = useBlock('home', 'features')

const cards = computed(() => published(block.value.cards))
const dials = computed<Record<string, any>[]>(() => Array.isArray(block.value.dials) ? block.value.dials : [])
</script>

<template>
  <section class="features">
    <div class="container">
      <div class="section__head">
        <h2 class="features__title section__title" v-html="rich(block.title, { accent: 'section__title-accent' })"></h2>
        <LayoutArrowLink :link="block.link" link-class="features__link arrow-link" />
      </div>

      <div class="features__grid">
        <LayoutCardLink
          v-for="(card, index) in cards"
          :key="index"
          :url="card.url"
          :class="['features__card', card.style && `features__card_${card.style}`]"
        >
          <span v-if="card.tag" class="features__tag">{{ card.tag }}</span>
          <div class="features__card-content">
            <h3 class="features__card-title" v-html="rich(card.title)"></h3>
            <div class="features__text">
              <p v-html="rich(card.text)"></p>
            </div>
            <div v-if="card.link_label" class="features__link arrow-link">{{ card.link_label }}</div>
          </div>
        </LayoutCardLink>
      </div>

      <LayoutArrowLink :link="block.link" link-class="arrow-link features__all" />

      <div v-if="dials.length" class="features__speed">
        <template v-for="(dial, index) in dials" :key="index">
          <div
            class="features__dial"
            :class="dial.color && `features__dial_${dial.color}`"
            :data-from="dial.from"
            :data-to="dial.to"
            :data-max="dial.max"
          >
            <svg class="features__dial-gauge" viewBox="0 0 450 450">
              <path class="features__dial-track"></path>
              <path class="features__dial-fill"></path>
            </svg>
            <div class="features__dial-needle">
              <svg width="165" height="71" viewBox="0 0 165 71" fill="none">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M0.794677 8.26214C-1.63635 14.793 1.69739 22.0896 8.23859 24.5323L164.577 70.0769L17.0433 0.781482C10.4667 -1.62052 3.17939 1.69348 0.794677 8.26214Z"
                  fill="#3F3E40" />
              </svg>
            </div>
            <div class="features__readout">
              <div class="features__readout-label">{{ dial.label }}</div>
              <div class="features__readout-value">{{ dial.to }}</div>
              <div class="features__readout-unit">{{ block.speed_unit }}</div>
            </div>
          </div>

          <div
            v-if="index === 0 && block.speed_text"
            class="features__mid"
            v-html="rich(block.speed_text)"
          ></div>
        </template>
      </div>
    </div>
  </section>
</template>
