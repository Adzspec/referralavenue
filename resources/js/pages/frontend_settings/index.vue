<template>
    <Head title="Frontend Settings" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Frontend Settings</h1>
            </div>

            <n-tabs type="line" animated>
                <n-tab-pane name="homePage" tab="Home Page">
                    <n-card>
                        <n-form label-placement="top" :model="form">
                            <template v-for="(value, key) in form" :key="key">
                                <!-- Array: Tags input -->
                                <n-form-item v-if="Array.isArray(value)" :label="formatLabel(key)" :path="`form.${key}`">
                                    <n-dynamic-tags v-model:value="form[key]" />
                                </n-form-item>
                                <!-- Textarea: description/content/long string -->
                                <n-form-item v-else-if="isTextarea(key, value)" :label="formatLabel(key)" :path="`form.${key}`">
                                    <n-input v-model:value="form[key]" type="textarea" :autosize="{ minRows: 2, maxRows: 4 }" />
                                </n-form-item>
                                <!-- Default: Text input -->
                                <n-form-item v-else :label="formatLabel(key)" :path="`form.${key}`">
                                    <n-input v-model:value="form[key]" />
                                </n-form-item>
                            </template>
                            <n-space justify="end" class="mt-4">
                                <n-button type="primary" native-type="submit" :loading="saving" @click="saveSettings">Save</n-button>
                            </n-space>
                        </n-form>
                    </n-card>
                </n-tab-pane>
                <n-tab-pane name="adtraction" tab="Adtraction Integrations">
                    <n-card>
                        <n-card class="mb-2">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-lg font-medium">{{ adtractionEnabled ? 'Disable' : 'Enable' }} Adtraction</span>
                                <n-switch v-model:value="adtractionEnabled" />
                            </div>
                        </n-card>
                        <n-form ref="adtractionFormRef" v-if="adtractionEnabled" :model="adtractionForm" :rules="adtractionRules">
                            <n-form-item label="Api Token" path="api_token">
                                <n-input placeholder="Enter api token here" v-model:value="adtractionForm.api_token" />
                            </n-form-item>
                            <n-form-item label="Channel ID" path="channel_id">
                                <n-input placeholder="Enter channel id here" v-model:value="adtractionForm.channel_id" />
                            </n-form-item>
                        </n-form>
                        <n-space justify="end" class="mt-4">
                            <n-button type="primary" :loading="savingAdtraction" @click="saveAdtraction"> Save Adtraction </n-button>
                        </n-space>
                    </n-card>
                </n-tab-pane>
                <n-tab-pane name="addrevenue" tab="Addrevenue Integrations">
                    <n-card>
                        <n-card class="mb-2">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-lg font-medium">Enable Addrevenue</span>
                                <n-switch v-model:value="addrevenueEnabled" />
                            </div>
                        </n-card>
                        <n-form ref="addrevenueFormRef" v-if="addrevenueEnabled" :model="addrevenueForm" :rules="addrevenueRules">
                            <n-form-item label="Api Token" path="api_token">
                                <n-input placeholder="Enter api token here" v-model:value="addrevenueForm.api_token" />
                            </n-form-item>
                            <n-form-item label="Channel ID" path="channel_id">
                                <n-input placeholder="Enter channel id here" v-model:value="addrevenueForm.channel_id" />
                            </n-form-item>
                        </n-form>
                        <n-space justify="end" class="mt-4">
                            <n-button type="primary" :loading="savingAddrevenue" @click="saveAddrevenue"> Save Addrevenue </n-button>
                        </n-space>

                        <n-card class="mt-2">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-lg font-medium">Sync Addrevenue</span>
                                <n-button v-if="addrevenueEnabled" :loading="syncing" @click="syncAddrevenue">
                                    Fetch from Addrevenue
                                </n-button>
                            </div>
                        </n-card>
                    </n-card>
                </n-tab-pane>
                <n-tab-pane name="tradedoubler" tab="Tradedoubler Integrations">
                    <n-card>
                        <n-card class="mb-2">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-lg font-medium"> {{ tradedoublerEnabled ? 'Disable' : 'Enable' }} Tradedoubler </span>
                                <n-switch v-model:value="tradedoublerEnabled" />
                            </div>
                        </n-card>

                        <n-form
                            ref="tradedoublerFormRef"
                            v-if="tradedoublerEnabled"
                            label-placement="top"
                            :model="tradedoublerForm"
                            :rules="tradedoublerRules"
                        >
                            <n-form-item label="Client ID" path="client_id">
                                <n-input v-model:value="tradedoublerForm.client_id" />
                            </n-form-item>
                            <n-form-item label="Client Secret" path="client_secret">
                                <n-input type="password" v-model:value="tradedoublerForm.client_secret" />
                            </n-form-item>
                            <n-form-item label="Username" path="username">
                                <n-input v-model:value="tradedoublerForm.username" />
                            </n-form-item>
                            <n-form-item label="Password" path="password">
                                <n-input type="password" v-model:value="tradedoublerForm.password" />
                            </n-form-item>
                        </n-form>

                        <n-space justify="end" class="mt-4">
                            <n-button type="primary" :loading="savingTradedoubler" @click="saveTradedoubler">Save Tradedoubler</n-button>
                        </n-space>
                    </n-card>
                </n-tab-pane>
            </n-tabs>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { useMessage } from 'naive-ui';
import { ref } from 'vue';

import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItemType } from '@/types';

import { useIntegrationForm } from '@/composables/useIntegrationForm';

const props = defineProps<{
    settings: Record<string, any>;
    adtraction: Record<string, any>;
    addrevenue: Record<string, any>;
    tradedoubler: Record<string, any>;
    can?: { edit?: boolean };
}>();

const {
    enabled: adtractionEnabled,
    form: adtractionForm,
    formRef: adtractionFormRef,
    rules: adtractionRules,
    saving: savingAdtraction,
    save: saveAdtraction,
} = useIntegrationForm('adtraction', props.adtraction?.credentials, props.adtraction?.status);

const {
    enabled: addrevenueEnabled,
    form: addrevenueForm,
    formRef: addrevenueFormRef,
    rules: addrevenueRules,
    saving: savingAddrevenue,
    save: saveAddrevenue,
} = useIntegrationForm('addrevenue', props.addrevenue?.credentials, props.addrevenue?.status);

const {
    enabled: tradedoublerEnabled,
    form: tradedoublerForm,
    formRef: tradedoublerFormRef,
    rules: tradedoublerRules,
    saving: savingTradedoubler,
    save: saveTradedoubler,
} = useIntegrationForm('tradedoubler', props.tradedoubler?.credentials, props.tradedoubler?.status);

const breadcrumbs: BreadcrumbItemType[] = [{ title: 'Frontend Settings', href: '/company/settings' }];

const message = useMessage();
const saving = ref(false);
// Make a reactive copy of settings (never mutate props directly!)
const form = ref({ ...props.settings });
console.log(props.adtraction);
function formatLabel(key: any) {
    // Convert snake_case/camelCase to Title Case
    return key
        .replace(/[_-]/g, ' ')
        .replace(/([a-z])([A-Z])/g, '$1 $2')
        .replace(/\b\w/g, (l: string) => l.toUpperCase());
}

function isTextarea(key: any, value: any) {
    return typeof value === 'string' && (key.toLowerCase().includes('description') || key.toLowerCase().includes('content') || value.length > 70);
}

const saveSettings = async () => {
    saving.value = true;
    try {
        // Use Inertia if you want a seamless update:
        router.put(
            '/company/settings',
            { settings: form.value },
            {
                onSuccess: () => message.success('Settings saved!'),
                onError: () => message.error('Could not save settings'),
            },
        );
    } finally {
        saving.value = false;
    }
};
const syncing = ref(false);
const syncAddrevenue = async () => {
    syncing.value = true;
    try {
        router.post(
            '/company/addrevenue/fetch',
            {},
            {
                onSuccess: () =>{
                    message.success('Addrevenue sync started!');
                },
                onError: (errors: any) => {
                    console.log(errors);
                    const backendMsg = errors?.message || 'Failed to start Addrevenue sync';
                    message.error(backendMsg);
                },
            }
        );
    } finally {
        syncing.value = false;
    }
};
</script>
