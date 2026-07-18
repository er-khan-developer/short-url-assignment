<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';
import { toast } from 'vue-sonner';

const props = defineProps({
    show: Boolean,
    companyId: Number,
    companyName:String
});

console.log('Props:', props); // Log the props to check their values
const emit = defineEmits(['close']);

const form = ref({
    longUrl: '',
});

const errors = ref<Record<string, string[]>>({});
const loading = ref(false);

const submit = async () => {
    loading.value = true;
    errors.value = {};

    try {
        const response = await axios.post(
            `/company/${props.companyId}/generate-short-url`,
            form.value
        );
        toast.success(response.data.message);
        form.value = {
            longUrl: ''
        };

        emit('close');

    } catch (error: any) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            toast.error(error.response?.data?.message || 'Something went wrong.');
        }
    } finally {
        loading.value = false;
    }
};
</script>

<template>
    <div
        v-if="show"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50"
    >
        <div class="bg-white rounded-lg shadow-lg w-full max-w-md p-6">

            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-semibold">
                    Generate Short Url for Company <span class="text-blue-600">{{ props.companyName }}</span>  
                </h3>

                <button
                    @click="emit('close')"
                    class="text-gray-500 text-2xl"
                >
                    &times;
                </button>
            </div>
            
           <div class="space-y-4 mb-4">
                    <lable>Orignal Url</lable>
                    <input  v-model="form.longUrl"
                        type="text"
                        placeholder="Enter Orignal Url"
                        class="w-full border rounded p-2"
                    />
                    <span
                        v-if="errors.longUrl"
                         class="text-red-500 text-sm mt-1"
                    >
                     {{ errors.longUrl[0] }}
                    </span>
                </div>

            <div class="flex justify-end gap-2 mt-6">

                <button
                    @click="emit('close')"
                    class="px-4 py-2 border rounded"
                >
                    Cancel
                </button>

                <button
                    @click="submit"
                    :disabled="loading"
                    class="px-4 py-2 bg-primary text-white rounded disabled:opacity-50"
                >
                    {{ loading ? 'Generating Short Url...' : 'Generate Short Url' }}
                </button>

            </div>

        </div>
    </div>
</template>