<script setup lang="ts">
import axios from 'axios'
import { computed, onMounted, ref, watch } from 'vue'
import { useRoute } from 'vue-router'
import LandingTemplate from '@/components/LandingTemplate.vue'
import { defaultLanding } from '@/config/defaultLanding'
import type { LandingConfig } from '@/types/landing'

const route = useRoute()
const landing = ref<LandingConfig>(structuredClone(defaultLanding))
const isLoading = ref(false)
const hasRemoteError = ref(false)

const currentSlug = computed(() => {
  const slug = route.params.slug
  return typeof slug === 'string' ? slug.trim() : ''

})


const mergeDeep = <T extends Record<string, any>>(base: T, source?: Record<string, any>): T => {
  const result = structuredClone(base)

  if (!source || typeof source !== 'object') {
    return result
  }

  Object.entries(source).forEach(([key, value]) => {
    if (Array.isArray(value)) {
      result[key as keyof T] = (value.length ? value : result[key as keyof T]) as T[keyof T]
      return
    }

    if (value && typeof value === 'object' && !Array.isArray(result[key as keyof T])) {
      result[key as keyof T] = mergeDeep((result[key as keyof T] as Record<string, any>) ?? {}, value) as T[keyof T]
      return
    }

    if (value !== undefined && value !== null && value !== '') {
      result[key as keyof T] = value as T[keyof T]
    }
  })

  return result
}

const loadLanding = async () => {
  if (!currentSlug.value) {
    hasRemoteError.value = true
    landing.value = structuredClone(defaultLanding)
    return
  }

  isLoading.value = true

  try {
    const { data } = await axios.get(`http://localhost:80/api/landings/${currentSlug.value}`)
    landing.value = mergeDeep(defaultLanding, data)
    hasRemoteError.value = false
  } catch {
    landing.value = structuredClone(defaultLanding)
    hasRemoteError.value = true
  } finally {
    isLoading.value = false
  }
}

watch(currentSlug, () => {
  void loadLanding()
})

onMounted(() => {
  void loadLanding()

})
</script>

<template>
  <div>
    <div v-if="isLoading" class="grid min-h-screen place-items-center bg-slate-50 text-slate-500">
      Загрузка лендинга...
    </div>

    <div v-else>
      <div v-if="hasRemoteError" class="px-4 pt-4 text-center text-sm text-amber-700">
        Лендинг по slug не найден или API недоступен. Показан шаблон по умолчанию.
      </div>
      <LandingTemplate :landing="landing" />
    </div>
  </div>
</template>