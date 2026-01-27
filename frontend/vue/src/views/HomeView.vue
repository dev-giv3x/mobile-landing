<script setup lang="ts">
import HeroBlock from '../components/HeroBlock.vue'
import BusinesTroubles from '../components/BusinesTroubles.vue'
import SolutionBlock from '../components/SolutionBlock.vue'
import AdventageBlock from '../components/AdventageBlock.vue'
import ContactFrom from '../components/ContactFrom.vue'
import { ref } from 'vue'
const contactFormRef = ref<InstanceType<typeof ContactForm> | null>(null)

const handleScroll = () => {
  const element = contactFormRef.value?.formSection

  if (element) {
    element.scrollIntoView({ behavior: 'smooth' })
  }
}
</script>

<template>
    <HeroBlock @on-click="handleScroll" />
    <BusinesTroubles />
    <SolutionBlock />
    <AdventageBlock />
    <ContactFrom ref="contactFormRef" id="contact-section" />
</template>
