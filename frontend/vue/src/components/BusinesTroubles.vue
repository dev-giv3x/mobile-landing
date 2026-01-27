<script lang="ts">
import {defineComponent} from 'vue'

export default defineComponent({
  name: "BusinesTrouble"
})
</script>

<template>
  <section class="mb-12">
    <div class="flex mt-12">
      <div class="w-[364px] h-px mr-[46.5px] bg-[linear-gradient(90deg,#EBF1F9_0%,#EDEDED_100%)] mt-[18px]"></div>
      <h2 class="text-3xl text-[#1A2B4B]">Проблемы бизнеса?</h2>
      <div class="w-[364px] h-px ml-[46.5px] bg-[linear-gradient(-90deg,#FFFFFF_0%,#EDEDED_100%)] mt-[18px]"></div>
    </div>
    <div class="flex mt-10 gap-6">
        <div class=" flex bg-[#FFFFFF] w-[350px] h-[112px] rounded-[12px]  shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-2px_rgba(0,0,0,0.1)]">
          <div class="py-8 pl-6">
            <img src="@/assets/image/busines-troubles-first.png" alt="image">
          </div>
              <div class="py-[30px] w-[183px] h-[45.75px] pl-4 ">
            <p class="text-lg text-[#1A2B4B] leading-6 font-normal ">Отсутствие единого инфополя</p>
            </div>
        </div>
      <div class=" flex bg-[#FFFFFF] w-[350px] h-[112px] rounded-[12px]  shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-2px_rgba(0,0,0,0.1)]">
        <div class="py-6 pl-6">
          <img src="@/assets/image/busines-troubles-second.png" alt="image">
        </div>
        <div class="py-[44.5px] w-[163px] h-[45.75px] pl-4 ">
          <p class="text-lg text-[#1A2B4B] font-normal ">Потеря данных</p>
        </div>
      </div>
      <div class=" flex bg-[#FFFFFF] w-[350px] h-[112px] rounded-[12px]  shadow-[0_4px_6px_-1px_rgba(0,0,0,0.1),0_2px_4px_-2px_rgba(0,0,0,0.1)]">
        <div class="py-4 pl-6">
          <img src="@/assets/image/busines-troubles-third.png" alt="image">
        </div>
        <div class="py-[30px] w-[163px] h-[45.75px] pl-4">
          <p class="text-lg text-[#1A2B4B] leading-6 font-normal">Сложная коммуникация</p>
        </div>
      </div>
    </div>
  </section>
</template>
