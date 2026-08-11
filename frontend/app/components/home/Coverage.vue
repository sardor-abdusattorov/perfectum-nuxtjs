<script setup lang="ts">
const block = useBlock('home', 'coverage')
const cities = computed(() => published(block.value.cities))
</script>

<template>
  <section class="coverage">
      <div class="container">
          <div class="coverage__head">
              <h2 class="section__title coverage__title">{{ block.title }}</h2>
              <div v-if="block.subtitle" class="coverage__subtitle">
                  <p>{{ block.subtitle }}</p>
              </div>
          </div>
          <div class="coverage__cities">
              <ul class="coverage__list">
                  <li v-for="(city, index) in cities" :key="index">
                      <div class="coverage__city">
                          <span class="coverage__pin" :class="city.active && 'coverage__pin_active'"></span>
                          <h3 class="coverage__name">{{ city.name }}</h3>
                          <div v-if="city.status_text" class="coverage__status">{{ city.status_text }}</div>
                      </div>
                  </li>
              </ul>
          </div>
      </div>
  </section>
</template>
