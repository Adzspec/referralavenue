<template>
    <div class="flex gap-2">
        <n-button
            v-for="action in actions"
            :key="action.label"
            :type="action.type || 'default'"
            :disabled="!selectedKeys.length"
            @click="handleAction(action)"
        >
            <template v-if="action.icon" #icon>
                <component :is="action.icon" />
            </template>
            {{ action.label }}
        </n-button>
    </div>
</template>

<script setup lang="ts">
import { useDialog } from 'naive-ui';


const props = defineProps<{
    selectedKeys: Array<number|string>,
    actions: Array<{
        label: string,
        type?: string,
        icon?: any,
        confirm?: string, // if set, will show confirm dialog
        handler: (selected: Array<number|string>) => void,
    }>,
}>();

const dialog = useDialog();

function handleAction(action: any) {
    if (!props.selectedKeys.length) return;

    if (action.confirm) {
        dialog.warning({
            title: 'Confirm',
            content: action.confirm,
            positiveText: 'Yes',
            negativeText: 'Cancel',
            onPositiveClick: () => {
                action.handler([...props.selectedKeys]);
            },
        });
    } else {
        action.handler([...props.selectedKeys]);
    }
}
</script>
