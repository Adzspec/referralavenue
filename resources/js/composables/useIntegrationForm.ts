import { ref } from 'vue';
import { router } from '@inertiajs/vue3';
import { useMessage } from 'naive-ui';
import type { FormInst, FormRules } from 'naive-ui';

const requiredRule = {
    required: true,
    message: 'This field is required',
    trigger: ['blur', 'input'],
};

const defaultRules: Record<string, any> = {
    api_token: [requiredRule],
    channel_id: [requiredRule],
};

const tradedoublerRules: Record<string, any> = {
    client_id: [requiredRule],
    client_secret: [requiredRule],
    username: [requiredRule],
    password: [requiredRule],
};

export function useIntegrationForm(
    provider: 'adtraction' | 'addrevenue' | 'tradedoubler',
    initialCredentials: Record<string, any> = {},
    initialStatus: boolean | number = 0
) {
    const formRef = ref<FormInst | null>(null);
    const enabled = ref(!!initialStatus);
    const form = ref({ ...initialCredentials });
    const saving = ref(false);
    const message = useMessage();

    let rules: FormRules = {};
    switch (provider) {
        case 'adtraction':
        case 'addrevenue':
            rules = defaultRules;
            break;
        case 'tradedoubler':
            rules = tradedoublerRules;
            break;
    }

    const save = async () => {
        if (!enabled.value) {
            // Just save disabled status
            saving.value = true;
            try {
                router.put(`/company/integrations/${provider}`, {
                    credentials: form.value,
                    status: enabled.value,
                }, {
                    onSuccess: () => message.success(`${provider} disabled`),
                    onError: () => message.error(`Failed to disable ${provider}`),
                });
            } finally {
                saving.value = false;
            }
            return;
        }

        const inst = formRef.value;
        if (!inst) return;

        const hasErrors = await inst.validate().then(() => false).catch(() => true);
        if (hasErrors) return;

        saving.value = true;
        try {
            router.put(`/company/integrations/${provider}`, {
                credentials: form.value,
                status: enabled.value,
            }, {
                onSuccess: () => message.success(`${provider} settings saved!`),
                onError: () => message.error(`Failed to save ${provider} settings`),
            });
        } finally {
            saving.value = false;
        }
    };

    return {
        enabled,
        form,
        formRef,
        rules,
        saving,
        save,
    };
}
