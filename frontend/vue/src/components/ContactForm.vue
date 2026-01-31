<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

const formSection = ref<HTMLElement | null>(null);

const handleScrollEvent = () => {
  formSection.value?.scrollIntoView({ behavior: 'smooth' });
};

onMounted(() => {
  window.addEventListener('scroll-to-contact', handleScrollEvent);
});

onUnmounted(() => {
  window.removeEventListener('scroll-to-contact', handleScrollEvent);
});

const name = ref('')
const phone = ref('')
const email = ref('')
const modal = ref<'success' | 'error' | null>(null)
const errors = ref({});
const isLoading = ref(false);

const sendForm = async () => {
  errors.value = {};
  isLoading.value = true;

  try {
    await axios.post('/api/send-form', {
      name: name.value,
      phone: phone.value,
      email: email.value
    });
    modal.value = 'success';
    name.value = '';
    phone.value = '';
    email.value = '';
  } catch (err) {
    if (err.response?.status === 422) {
      errors.value = err.response.data.errors;
    } else {
      modal.value = 'error';
    }
  } finally {
    isLoading.value = false;
  }
}

const closeModal = () => {
  modal.value = null
}

defineExpose({
  formSection
})

</script>

<template>
  <section ref="formSection" class="px-4">
    <div class="mt-[112px] flex justify-center text-center">
      <h2 class="text-2xl max-[376px]:text-[18px] text-[#1A2B4B]">Готовы вывести бизнес на новый уровень?</h2>
    </div>
    <div class="flex justify-center mt-8">
      <form @submit.prevent="sendForm" class="space-y-4 w-full max-w-[512px]">
        <div>
          <input v-model="name" placeholder="Ваше имя" :class="['w-full bg-white placeholder:text-[#1A2B4B] border px-4 py-3 focus:outline-none focus:border-[#a3aab3] rounded transition-colors',errors.name ? 'border-red-500' : 'border-[#D1D5DB]']"/>
          <span v-if="errors.name" class="text-red-500 text-xs mt-1">{{ errors.name[0] }}</span>
        </div>
        <div>
          <input v-model="phone" placeholder="Телефон" :class="['w-full bg-white placeholder:text-[#1A2B4B] border px-4 py-3 focus:outline-none focus:border-[#a3aab3] rounded transition-colors',errors.phone ? 'border-red-500' : 'border-[#D1D5DB]']"/>
          <span v-if="errors.phone" class="text-red-500 text-xs mt-1">{{ errors.phone[0] }}</span>
        </div>
        <div class="flex flex-col md:flex-row gap-4 items-start"> <div class="flex flex-col w-full md:flex-1">
          <input v-model="email" type="email" placeholder="E-mail" :class="['w-full bg-white placeholder:text-[#1A2B4B] border px-4 py-3 focus:outline-none rounded transition',errors.email ? 'border-red-500' : 'border-[#D1D5DB] focus:border-[#a3aab3]']"/>
          <span v-if="errors.email" class="text-red-500 text-xs mt-1">{{ errors.email[0] }}</span>
        </div>
          <button type="submit" :disabled="isLoading" class="text-[#FFFFFF] cursor-pointer bg-[#1D65C1] hover:bg-[#154a8d] w-full md:max-w-[195px] px-8 py-[13px] active:scale-95 transition-all rounded whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed disabled:scale-100">
            <span v-if="isLoading">Отправка...</span>
            <span v-else>Оставить заявку</span>
          </button>        </div>
        <div class="flex justify-center">
          <p class="text-sm text-gray-500">Консультация и демонстрация бесплатно</p>
        </div>
      </form>
    </div>
    <transition name="fade">
      <div v-if="modal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50" @click.self="closeModal">
        <transition name="popup">
          <div class="bg-white  rounded-lg p-6 w-[90%] max-w-md text-center shadow-xl">
            <h2 class="text-xl font-semibold text-[#1A2B4B] mb-4">
              {{ modal === 'success' ? 'Заявка отправлена!' : 'Ошибка отправки' }}
            </h2>
            <p class="text-gray-600 mb-6">
              {{ modal === 'success' ? 'В ближайшее время с вами свяжутся.' : 'Не удалось отправить заявку. Попробуйте позже.' }}
            </p>
            <button @click="closeModal" class=" cursor-pointer bg-blue-600 text-white px-6 py-2 rounded">Продолжить</button>
          </div>
        </transition>
      </div>
    </transition>
  </section>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity .25s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
.popup-enter-active, .popup-leave-active { transition: all .25s ease; }
.popup-enter-from, .popup-leave-to { opacity: 0; transform: scale(0.9); }
</style>