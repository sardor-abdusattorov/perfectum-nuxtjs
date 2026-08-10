<script setup lang="ts">
const root = ref<HTMLElement | null>(null)

useMarquee(root)
</script>

<template>
  <div ref="root" class="marquee">
      <div class="marquee__track">
          <span class="marquee__star">

          </span>
          <span class="marquee__item">STANDALONE</span>
          <span class="marquee__star">

          </span>
          <span class="marquee__item">VONR</span>
          <span class="marquee__star">

          </span>
          <span class="marquee__item">
              <img src="/images/perfectum-marquee.svg" alt="">
          </span>
          <span class="marquee__star">

          </span>
          <span class="marquee__item">БЕСПРОВОДНОЙ ИНТЕРНЕТ</span>
      </div>
  </div>
</template>
