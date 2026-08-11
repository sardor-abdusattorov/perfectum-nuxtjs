<script setup lang="ts">
const block = useBlock('home', 'features')
const cards = computed(() => published(block.value.cards))
const dials = computed(() => published(block.value.dials))
const lines = (value: unknown) => String(value ?? '').split('\n')
</script>

<template>
  <section class="features">
      <div class="container">
          <div class="section__head">
              <h2 class="features__title section__title">
                  {{ block.title }} <br class="features__title-break" /><span
                      class="section__title-accent">{{ block.title_accent }}</span>
              </h2>
              <LayoutArrowLink :link="block.link" link-class="features__link arrow-link">
                  
              </LayoutArrowLink>
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
                      <h3 class="features__card-title">
                          <template v-for="(line, i) in lines(card.title)" :key="i">
                              <br v-if="i" />{{ line }}
                          </template>
                      </h3>
                      <div class="features__text">
                          <p>
                              <template v-for="(line, i) in lines(card.text)" :key="i">
                                  <br v-if="i" />{{ line }}
                              </template>
                          </p>
                      </div>
                      <div v-if="card.link_label" class="features__link arrow-link">
                          {{ card.link_label }}
                          
                      </div>
                  </div>
              </LayoutCardLink>
          </div>
          <LayoutArrowLink :link="block.link" link-class="arrow-link features__all">
              
          </LayoutArrowLink>
          <div v-if="dials.length" class="features__speed">
              <template v-for="(dial, index) in dials" :key="index">
                  <div v-if="index" class="features__mid">
                      <template v-for="(line, i) in lines(block.speed_text)" :key="i">
                          <br v-if="i" />{{ line }}
                      </template>
                  </div>
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
              </template>
          </div>
      </div>
  </section>
</template>
