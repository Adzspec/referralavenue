<script setup lang="ts">
import { NConfigProvider, NMessageProvider, NDialogProvider } from 'naive-ui';
</script>

<template>
    <NConfigProvider>
        <NDialogProvider>
            <NMessageProvider>
                <slot />
            </NMessageProvider>
        </NDialogProvider>
    </NConfigProvider>
</template>
