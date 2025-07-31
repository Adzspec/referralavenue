<template>
    <n-drawer
        :show="props.modelValue"
        @update:show="(val:any) => emit('update:modelValue', val)"
        placement="right"
        :width="360"
    >
        <n-drawer-content title="Filter Offers" closable>
            <n-form :model="model" label-placement="top">
                <n-form-item label="Store">
                    <n-select v-model:value="model.store_id" :options="storeOptions" clearable />
                </n-form-item>
                <n-form-item label="Status">
                    <n-select
                        v-model:value="model.status"
                        :options="[
                            { label: 'Active', value: 1 },
                            { label: 'Inactive', value: 0 }
                        ]"
                        clearable
                    />
                </n-form-item>
                <n-form-item label="Featured">
                    <n-select
                        v-model:value="model.is_featured"
                        :options="[
                            { label: 'Featured', value: 1 },
                            { label: 'Not Featured', value: 0 }
                        ]"
                        clearable
                    />
                </n-form-item>
                <n-form-item label="Exclusive">
                    <n-select
                        v-model:value="model.is_exclusive"
                        :options="[
                            { label: 'Exclusive', value: 1 },
                            { label: 'Not Exclusive', value: 0 }
                        ]"
                        clearable
                    />
                </n-form-item>
                <n-form-item label="Search">
                    <n-input v-model:value="model.search" placeholder="Search by title" clearable />
                </n-form-item>
                <n-space justify="end">
                    <n-button @click="reset">Reset</n-button>
                    <n-button type="primary" @click="submit">Apply</n-button>
                </n-space>
            </n-form>
        </n-drawer-content>
    </n-drawer>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

const props = defineProps<{
    modelValue: boolean;
    filters: Record<string, any>;
    stores: { id: number; name: string }[];
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
    (e: 'update:filters', filters: Record<string, any>): void;
}>();

const model = ref({ ...props.filters });

watch(() => props.filters, (val) => {
    model.value = { ...val };
});

const storeOptions = props.stores.map((store) => ({
    label: store.name,
    value: store.id,
}));

const reset = () => {
    model.value = {
        store_id: null,
        status: null,
        is_featured: null,
        is_exclusive: null,
        search: '',
    };
};

const submit = () => {
    emit('update:filters', { ...model.value }); // pass up to parent
    emit('update:modelValue', false);
    // Do not call router.get here. Let parent handle it.
};
</script>
