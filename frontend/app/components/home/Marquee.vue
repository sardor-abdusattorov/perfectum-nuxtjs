<script setup lang="ts">
const block = useBlock('home', 'marquee')
const items = computed(() => published(block.value.items))
</script>

<template>
  <div v-if="items.length" class="marquee">
      <div class="marquee__track">
          <template v-for="(item, index) in items" :key="index">
              <span class="marquee__star"></span>
              <span class="marquee__item">
                  <img v-if="item.image" :src="item.image" :alt="item.text ?? ''">
                  <template v-else>{{ item.text }}</template>
              </span>
          </template>
      </div>
  </div>
</template>
