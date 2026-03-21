<script setup lang="ts">
import { computed } from 'vue'
import type { LandingConfig } from '@/types/landing'

const props = defineProps<{
  landing: LandingConfig
}>()

const primaryColor = computed(() => props.landing.settings.primary_color || '#1D65C1')

const iconModules = import.meta.glob('../assets/icons/*.svg', {
  eager: true,
  import: 'default',
}) as Record<string, string>

const resolveIcon = (name?: string | null) => {
  if (!name) return ''
  return iconModules[`../assets/icons/${name}.svg`] ?? ''
}

const structureItems = computed(() => [
  {
    title: props.landing.content.structure.home_title,
    description: props.landing.content.structure.home_description,
  },
  {
    title: props.landing.content.structure.services_title,
    description: props.landing.content.structure.services_description,
  },
  {
    title: props.landing.content.structure.communications_title,
    description: props.landing.content.structure.communications_description,
  },
])
</script>

<template>
  <main class="min-h-screen bg-[radial-gradient(circle_at_top,_#edf5ff_0%,_#f8fbff_36%,_#ffffff_100%)] text-slate-900 font-[Inter,Arial,sans-serif]">

    <!-- Hero -->
    <section v-if="landing.content.hero.enabled" class="px-4 pt-6 md:pt-10">
      <div
          class="mx-auto grid max-w-[1200px] gap-8 rounded-[36px] border p-6 shadow-[0_30px_100px_rgba(15,23,42,0.08)] md:p-10"
          :style="{ background: `${primaryColor}0B`, borderColor: `${primaryColor}33` }"
          style="grid-template-columns: minmax(0, 1.1fr) minmax(120px, 0.9fr); align-items: center;"
      >
        <div class="flex flex-col justify-center">
          <div
              class="w-fit text-xs font-bold uppercase tracking-[0.08em] px-4 py-2 rounded-full"
              :style="{ color: primaryColor }"
          >
            {{ landing.content.hero.eyebrow }}
          </div>
          <h1 class="mt-4 text-4xl leading-tight font-extrabold tracking-tight text-[#10203d] md:text-5xl">
            {{ landing.content.hero.title }}
          </h1>
          <p class="mt-4 text-base leading-relaxed text-[#536277] md:text-lg">
            {{ landing.content.hero.subtitle }}
          </p>
        </div>

        <div class="flex min-h-[160px] items-center justify-center">
          <img
              v-if="landing.content.hero.image"
              :src="landing.content.hero.image"
              :alt="landing.content.hero.image_alt || landing.company_name"
              class="w-full max-w-[220px] max-h-[220px] object-contain"
          />
          <div
              v-else
              class="w-full max-w-[220px] aspect-square rounded-[20px] border border-dashed"
              :style="{ background: `linear-gradient(135deg, ${primaryColor}22 0%, #ffffff 100%)`, borderColor: `${primaryColor}55` }"
          ></div>
        </div>
      </div>
    </section>

    <!-- Header -->
    <div v-if="!landing.content.hero.enabled" class="px-4 pt-6 md:pt-10">
      <div class="mx-auto flex max-w-[1200px] items-center justify-between gap-4">
        <div>
          <div class="text-2xl font-bold leading-tight">{{ landing.title || landing.company_name }}</div>
          <div class="mt-1 text-sm text-[#6b7a90]">/{{ landing.slug }}</div>
        </div>
        <img
            v-if="landing.settings.logo"
            :src="landing.settings.logo"
            :alt="landing.company_name"
            class="h-12 w-12 rounded-xl border border-slate-200 bg-white object-contain p-1.5"
        />
      </div>
    </div>

    <!-- Goals -->
    <section class="px-4 pt-16">
      <div class="mx-auto max-w-[1120px]">
        <h2 class="text-center text-3xl font-bold tracking-tight text-[#10203d] md:text-4xl">
          {{ landing.content.goals.section_title }}
        </h2>
        <div class="mt-10 grid gap-4">
          <article
              v-for="(item, index) in landing.content.goals.items"
              :key="`goal-${index}`"
              class="flex min-h-[84px] items-center gap-4 rounded-[18px] border border-slate-200 bg-white p-4"
          >
            <div class="shrink-0">
              <img v-if="item.image" :src="item.image" :alt="item.title" class="h-12 w-12 object-contain rounded-xl" />
              <div v-else class="h-12 w-12 rounded-[14px]" :style="{ backgroundColor: `${primaryColor}12` }"></div>
            </div>
            <p class="text-sm font-semibold leading-snug text-slate-900">{{ item.title }}</p>
          </article>
        </div>
      </div>
    </section>

    <!-- Functionality (Modules) -->
    <section class="px-4 pt-16">
      <div class="mx-auto max-w-[1120px]">
        <div class="mx-auto max-w-[760px] text-center">
          <h2 class="text-3xl font-bold tracking-tight text-[#10203d] md:text-4xl">
            {{ landing.content.functionality.section_title }}
          </h2>
          <p class="mt-4 text-base leading-relaxed text-[#536277] md:text-lg">
            {{ landing.content.functionality.description }}
          </p>
        </div>

        <div class="mt-12 grid gap-5 md:grid-cols-2 xl:grid-cols-3">
          <article
              v-for="(module, index) in landing.content.modules"
              :key="`module-${index}`"
              class="rounded-[20px] border border-slate-200 bg-white p-4 shadow-[0_8px_30px_rgba(15,23,42,0.05)]"
          >
            <div class="flex items-center justify-between gap-3">
              <h3 class="text-base font-bold leading-snug text-slate-900">{{ module.title }}</h3>
              <div
                  class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl"
                  :style="{ backgroundColor: `${primaryColor}18` }"
              >
                <!-- Primary icon as mask -->
                <div
                    v-if="resolveIcon(module.primary_icon)"
                    class="icon-mask h-5 w-5"
                    :style="{
                        backgroundColor: primaryColor,
                        maskImage: `url(${resolveIcon(module.primary_icon)})`,
                        WebkitMaskImage: `url(${resolveIcon(module.primary_icon)})`
                    }"
                ></div>
                <div v-else class="h-4 w-4 rounded-md" :style="{ backgroundColor: `${primaryColor}55` }"></div>
              </div>
            </div>

            <div class="my-3 h-px bg-slate-100"></div>
            <p class="text-sm leading-relaxed text-[#536277]">{{ module.content }}</p>
            <div class="my-3 h-px bg-slate-100"></div>

            <div class="flex items-center gap-2 text-sm leading-relaxed text-[#536277]">
              <div
                  class="flex h-5 w-5 shrink-0 items-center justify-center rounded-full"
                  :style="{ backgroundColor: `${primaryColor}12` }"
              >
                <!-- Secondary icon as mask -->
                <div
                    v-if="resolveIcon(module.secondary_icon)"
                    class="icon-mask h-3 w-3"
                    :style="{
                        backgroundColor: primaryColor,
                        maskImage: `url(${resolveIcon(module.secondary_icon)})`,
                        WebkitMaskImage: `url(${resolveIcon(module.secondary_icon)})`
                    }"
                ></div>
              </div>
              <span>{{ module.secondary_text }}</span>
            </div>
          </article>
        </div>
      </div>
    </section>

    <!-- Structure -->
    <section class="px-4 pt-16">
      <div class="mx-auto max-w-[1120px]">
        <h2 class="text-center text-3xl font-bold tracking-tight text-[#10203d] md:text-4xl">
          {{ landing.content.structure.section_title }}
        </h2>
        <div class="mt-10 grid gap-4">
          <article
              v-for="(item, index) in structureItems"
              :key="`structure-${index}`"
              class="rounded-[18px] border border-slate-200 bg-white p-[18px]"
          >
            <div
                class="inline-flex rounded-full px-2.5 py-1 text-[11px] font-bold uppercase tracking-[0.08em]"
                :style="{ color: primaryColor, backgroundColor: `${primaryColor}12` }"
            >
              Экран
            </div>
            <h3 class="mt-3 text-base font-bold leading-snug text-[#10203d]">{{ item.title }}</h3>
            <p class="mt-2.5 text-sm leading-relaxed text-[#536277]">{{ item.description }}</p>
          </article>
        </div>
      </div>
    </section>

    <!-- Advantages -->
    <section class="px-4 pt-16 pb-16">
      <div class="mx-auto max-w-[1120px]">
        <h2 class="text-center text-3xl font-bold tracking-tight text-[#10203d] md:text-4xl">
          {{ landing.content.advantages.section_title }}
        </h2>
        <div class="mt-10 grid gap-5 md:grid-cols-3">
          <div
              v-for="(item, index) in landing.content.advantages.items"
              :key="`advantage-${index}`"
              class="rounded-[18px] border border-slate-200 bg-white px-6 py-[18px] text-center"
          >
            <div class="mx-auto mb-3 flex h-[72px] w-[72px] items-center justify-center rounded-[20px]" :style="{ backgroundColor: `${primaryColor}12` }">
              <img v-if="item.image" :src="item.image" :alt="item.title" class="h-[56px] w-[56px] object-contain" />
              <div v-else class="h-8 w-8 rounded-2xl bg-white/60"></div>
            </div>
            <p class="text-sm font-semibold leading-snug text-slate-900">{{ item.title }}</p>
          </div>
        </div>
      </div>
    </section>

  </main>
</template>

<style scoped>
.icon-mask {
  background-color: currentColor;
  mask-size: contain;
  mask-repeat: no-repeat;
  mask-position: center;
  -webkit-mask-size: contain;
  -webkit-mask-repeat: no-repeat;
  -webkit-mask-position: center;
}
</style>