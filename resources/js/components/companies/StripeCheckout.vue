<template>
  <div>
    <form @submit.prevent="handleSubmit">
      <div ref="cardElement" class="mb-4"></div>
      <button type="submit" :disabled="loading || !stripe || !elements" class="btn btn-primary">
        <span v-if="loading">Processing...</span>
        <span v-else>Pay</span>
      </button>
      <div v-if="error" class="text-danger mt-2">{{ error }}</div>
    </form>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { loadStripe } from '@stripe/stripe-js';

const props = defineProps({
  companyId: Number,
  subscriptionId: Number,
});

const stripe = ref(null);
const elements = ref(null);
const cardElement = ref(null);
const card = ref(null);
const clientSecret = ref('');
const loading = ref(false);
const error = ref('');

onMounted(async () => {
  stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_KEY);
  if (!stripe.value) {
    error.value = 'Stripe failed to load.';
    return;
  }
  // Get client secret from backend
  const res = await fetch('/stripe/payment-intent', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify({
      company_id: props.companyId,
      subscription_id: props.subscriptionId,
    }),
  });
  const data = await res.json();
  clientSecret.value = data.clientSecret;
  elements.value = stripe.value.elements();
  card.value = elements.value.create('card');
  card.value.mount(cardElement.value);
});

const handleSubmit = async () => {
  loading.value = true;
  error.value = '';
  const { paymentIntent, error: stripeError } = await stripe.value.confirmCardPayment(clientSecret.value, {
    payment_method: {
      card: card.value,
    },
  });
  if (stripeError) {
    error.value = stripeError.message;
  } else if (paymentIntent && paymentIntent.status === 'succeeded') {
    // Payment successful
    window.location.reload();
  }
  loading.value = false;
};
</script>

<style scoped>
.btn {
  padding: 0.5rem 1.5rem;
  font-size: 1rem;
}
</style> 