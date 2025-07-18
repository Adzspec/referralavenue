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

    /**
     * Upload files (single or FormData) to a specific URL.
     * @param url - upload endpoint
     * @param fileOrFormData - File, Blob, or FormData
     * @param fieldName - field name for the file (default: 'file')
     * @returns Promise<T> - backend response (usually an object)
     */
    const upload = async <T = any>(
        url: string,
        fileOrFormData: File | Blob | FormData,
        fieldName: string = 'file'
    ): Promise<T> => {
        let formData: FormData;
        if (fileOrFormData instanceof FormData) {
            formData = fileOrFormData;
        } else {
            formData = new FormData();
            formData.append(fieldName, fileOrFormData);
        }
        try {
            const res = await fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                }
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Upload failed');
            message.success('Upload successful!');
            return data as T;
        } catch (error: any) {
            message.error(error.message || 'Upload failed');
            throw error;
        }
    };
    return { create, update, destroy, upload };
}
