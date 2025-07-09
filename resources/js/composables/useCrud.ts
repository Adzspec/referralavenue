import { router } from '@inertiajs/vue3';
import { useMessage, useDialog } from 'naive-ui';

export function useCrud({ baseUrl, reload = true }: { baseUrl: string, reload?: boolean }) {
    const message = useMessage();
    const dialog = useDialog();

    const create = (data: any, options = { onSuccess: () => {}, onError: () => {} }) => {
        router.post(baseUrl, data, {
            onSuccess: () => {
                // message.success('Created successfully');
                if (reload) {
                    router.reload();
                }
                options.onSuccess();
            },
            onError: () => {
                message.error('Failed to create');
                options.onError();
            }
        });
    };

    const update = (id: number, data: any, options = { onSuccess: () => {}, onError: () => {} }) => {
        router.put(`${baseUrl}/${id}`, data, {
            onSuccess: () => {
                // message.success('Updated successfully');
                if (reload) {
                    router.reload();
                }
                options.onSuccess();
            },
            onError: () => {
                message.error('Failed to update');
                options.onError();
            }
        });
    };

    const destroy = (id: number, confirmTitle = 'Delete Record', confirmMessage = 'Are you sure?', options = { onSuccess: () => {}, onError: () => {} }) => {
        dialog.warning({
            title: confirmTitle,
            content: confirmMessage,
            positiveText: 'Yes',
            negativeText: 'Cancel',
            onPositiveClick: () => {
                router.delete(`${baseUrl}/${id}`, {
                    onSuccess: () => {
                        message.success('Deleted successfully');
                        if (reload) {
                            router.reload();
                        }
                        options.onSuccess();
                    },
                    onError: () => {
                        message.error('Failed to delete');
                        options.onError();
                    }
                });
            },
        });
    };

    return { create, update, destroy };
}
