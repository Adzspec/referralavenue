<template>
    <div class="flex flex-col items-center justify-center h-96">
        <n-result
            status="info"
            title="Redirecting to Stripe"
            description="Please wait, you are being redirected to Stripe for secure payment."
        />
    </div>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
const props = defineProps({ stripeUrl: String });

onMounted(() => {
    if (props.stripeUrl) {
        window.location.href = props.stripeUrl;
    }
});
</script>
