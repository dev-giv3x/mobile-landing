<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
  name: "HeroBlock"
})
</script>

<template>
        <section class="">
          <div class="pt-12 flex justify-between gap-40">
            <div>
              <div class="title">
                <h1 class="text-[#1A2B4B] text-5xl max-w-[560px] pt-16 max-[768px]:w-[387px] max-[375px]:w-[211px] max-[375px]:text-[20px] ">Корпоративное приложение для вашего бизнеса</h1>
              </div>
              <div class="subtitle">
                <p class="text-[#5F738C] text-lg pt-4 pb-4">Единый цифровой офис для вашей компании</p>
              </div>
              <div class="buttons flex gap-4">
                <button class="text-[#FFFFFF] bg-[#1D65C1] max-w-[195px] w-full h-full max-h-[50px] px-8 py-[13px] active:scale-95 transition-transform cursor-pointer rounded">Оставить заявку</button>
                <button class="text-[#1A2B4B] flex-1 max-w-[195px] w-full h-full max-h-[50px] border border-[#D1D5DB] px-8 py-[13px] active:scale-95 transition-transform cursor-pointer rounded">Получить демо</button>
              </div>
            </div>
            <div>
              <div class="">
                <img src="@/assets/image/hero-block-image.png" class="max-w-[350px] w-full h-auto object-contain" alt="image">
              </div>
            </div>
          </div>
        </section>
</template>

