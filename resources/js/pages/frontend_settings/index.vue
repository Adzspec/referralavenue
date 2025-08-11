<template>
    <Head title="Frontend Settings" />
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="p-6">
            <div class="mb-4 flex items-center justify-between">
                <h1 class="text-2xl font-semibold">Frontend Settings</h1>
            </div>

            <n-tabs type="card" animated placement="left">
                <!-- Addrevenue -->
                <n-tab-pane name="addrevenue" tab="Addrevenue Integrations">
                    <n-card>
                        <n-card class="mb-2">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-lg font-medium">Enable Addrevenue</span>
                                <n-switch
                                    :value="addrevenueEnabled"
                                    @update:value="onToggle('addrevenue', $event)"
                                />
                            </div>
                            <n-alert
                                v-if="limitAlert.addrevenue"
                                title="Upgrade Subscription"
                                type="warning"
                            >
                                Integration limit reached. Please upgrade your subscription to enable Addrevenue.
                            </n-alert>
                        </n-card>

                        <n-form
                            ref="addrevenueFormRef"
                            v-if="addrevenueEnabled"
                            :model="addrevenueForm"
                            :rules="addrevenueRules"
                        >
                            <n-form-item label="Api Token" path="api_token">
                                <n-input placeholder="Enter api token here" v-model:value="addrevenueForm.api_token" />
                            </n-form-item>
                            <n-form-item label="Channel ID" path="channel_id">
                                <n-input placeholder="Enter channel id here" v-model:value="addrevenueForm.channel_id" />
                            </n-form-item>
                        </n-form>

                        <n-space justify="end" class="mt-4">
                            <n-button type="primary" :loading="savingAddrevenue" @click="saveAddrevenue">
                                Save Addrevenue
                            </n-button>
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

                <!-- Adtraction -->
                <n-tab-pane name="adtraction" tab="Adtraction Integrations">
                    <n-card>
                        <n-card class="mb-2">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-lg font-medium">{{ adtractionEnabled ? 'Disable' : 'Enable' }} Adtraction</span>
                                <n-switch
                                    :value="adtractionEnabled"
                                    @update:value="onToggle('adtraction', $event)"
                                />
                            </div>
                            <n-alert
                                v-if="limitAlert.adtraction"
                                title="Upgrade Subscription"
                                type="warning"
                            >
                                Integration limit reached. Please upgrade your subscription to enable Adtraction.
                            </n-alert>
                        </n-card>

                        <n-form
                            ref="adtractionFormRef"
                            v-if="adtractionEnabled"
                            :model="adtractionForm"
                            :rules="adtractionRules"
                        >
                            <n-form-item label="Api Token" path="api_token">
                                <n-input placeholder="Enter api token here" v-model:value="adtractionForm.api_token" />
                            </n-form-item>
                            <n-form-item label="Channel ID" path="channel_id">
                                <n-input placeholder="Enter channel id here" v-model:value="adtractionForm.channel_id" />
                            </n-form-item>
                        </n-form>

                        <n-space justify="end" class="mt-4">
                            <n-button type="primary" :loading="savingAdtraction" @click="saveAdtraction">
                                Save Adtraction
                            </n-button>
                        </n-space>
                    </n-card>
                </n-tab-pane>

                <!-- Tradedoubler -->
                <n-tab-pane name="tradedoubler" tab="Tradedoubler Integrations">
                    <n-card>
                        <n-card class="mb-2">
                            <div class="mb-4 flex items-center justify-between">
                                <span class="text-lg font-medium">{{ tradedoublerEnabled ? 'Disable' : 'Enable' }} Tradedoubler</span>
                                <n-switch
                                    :value="tradedoublerEnabled"
                                    @update:value="onToggle('tradedoubler', $event)"
                                />
                            </div>
                            <n-alert
                                v-if="limitAlert.tradedoubler"
                                title="Upgrade Subscription"
                                type="warning"
                            >
                                Integration limit reached. Please upgrade your subscription to enable Tradedoubler.
                            </n-alert>
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
                            <n-button type="primary" :loading="savingTradedoubler" @click="saveTradedoubler">
                                Save Tradedoubler
                            </n-button>
                        </n-space>
                    </n-card>
                </n-tab-pane>
            </n-tabs>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { useIntegrationForm } from '@/composables/useIntegrationForm';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItemType } from '@/types';
import { Head, router } from '@inertiajs/vue3';
import { useMessage } from 'naive-ui';
import { ref, computed } from 'vue';

const props = defineProps<{
    adtraction: Record<string, any>;
    addrevenue: Record<string, any>;
    tradedoubler: Record<string, any>;
    can?: { edit?: boolean };
    canUseAffiliateNetwork: boolean;     // fallback when numeric limit isn't provided
    affiliateNetworkLimit?: number;      // plan key: affiliate_network_integrations
}>();

// Composables per integration
const {
    enabled: adtractionEnabled,
    form: adtractionForm,
    formRef: adtractionFormRef,
    rules: adtractionRules,
    saving: savingAdtraction,
    save: saveAdtraction
} = useIntegrationForm('adtraction', props.adtraction?.credentials, props.adtraction?.status);

const {
    enabled: addrevenueEnabled,
    form: addrevenueForm,
    formRef: addrevenueFormRef,
    rules: addrevenueRules,
    saving: savingAddrevenue,
    save: saveAddrevenue
} = useIntegrationForm('addrevenue', props.addrevenue?.credentials, props.addrevenue?.status);

const {
    enabled: tradedoublerEnabled,
    form: tradedoublerForm,
    formRef: tradedoublerFormRef,
    rules: tradedoublerRules,
    saving: savingTradedoubler,
    save: saveTradedoubler
} = useIntegrationForm('tradedoubler', props.tradedoubler?.credentials, props.tradedoubler?.status);

// Count how many are enabled (reactive)
const enabledCount = computed(() =>
    Number(!!adtractionEnabled.value) +
    Number(!!addrevenueEnabled.value) +
    Number(!!tradedoublerEnabled.value)
);

// Per-integration warning flags (only shown when user ATTEMPTS to enable but blocked)
const limitAlert = ref({
    adtraction: false,
    addrevenue: false,
    tradedoubler: false
});

// Helper maps
type Key = 'adtraction' | 'addrevenue' | 'tradedoubler';
const stateByKey: Record<Key, any> = {
    adtraction: adtractionEnabled,
    addrevenue: addrevenueEnabled,
    tradedoubler: tradedoublerEnabled
};

// Toggle handler that prevents enabling beyond limit and never warns on disabling
function onToggle(key: Key, nextValue: boolean) {
    const state = stateByKey[key] as { value: boolean };
    const current = !!state.value;

    // If disabling, always allow and clear alert
    if (!nextValue) {
        state.value = false;
        limitAlert.value[key] = false;
        return;
    }

    // If enabling, check limits
    const otherEnabled = enabledCount.value - Number(current); // exclude current integration
    let blocked = false;

    if (typeof props.affiliateNetworkLimit === 'number') {
        const limit = props.affiliateNetworkLimit;
        const wouldTotal = otherEnabled + 1;
        blocked = wouldTotal > limit;
    } else {
        // Fallback boolean: if backend says "can't add more", block enabling
        blocked = !props.canUseAffiliateNetwork;
    }

    if (blocked) {
        // Do not enable; show alert for this integration only
        state.value = false;
        limitAlert.value[key] = true;
    } else {
        // Enable and clear any previous alert
        state.value = true;
        limitAlert.value[key] = false;
    }
}

const breadcrumbs: BreadcrumbItemType[] = [{ title: 'Frontend Settings', href: '/company/settings' }];
const message = useMessage();
const syncing = ref(false);

const syncAddrevenue = async () => {
    syncing.value = true;
    try {
        router.post('/company/addrevenue/fetch', {}, {
            onSuccess: () => message.success('Addrevenue sync started!'),
            onError: (errors: any) => {
                const backendMsg = errors?.message || 'Failed to start Addrevenue sync';
                message.error(backendMsg);
            }
        });
    } finally {
        syncing.value = false;
    }
};
</script>
