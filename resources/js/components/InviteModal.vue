<script setup lang="ts">
import { ref } from 'vue';
import axios from 'axios';
import { toast } from 'vue-sonner';

const props = defineProps({
    show: Boolean,
    companyId: Number,
    companyName: String,
});

console.log('Props:', props); // Log the props to check their values
const emit = defineEmits(['close']);

const form = ref({
    name: '',
    email: '',
    role: 'Admin',
    company_name: props.companyName, // Add company_name to the form data
});

const errors = ref<Record<string, string[]>>({});
const loading = ref(false);

const submit = async () => {
    loading.value = true;
    errors.value = {};
    form.value.company_name = props.companyName

    try {
        const response = await axios.post(
            `/company/${props.companyId}/invite`,
            form.value
        );
        toast.success(response.data.message);
        form.value = {
            name: '',
            email: '',
            role: 'Admin',
            company_name: props.companyName
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
                    Invite User for Company <span class="text-blue-600">{{ props.companyName }}</span>  
                </h3>

                <button
                    @click="emit('close')"
                    class="text-gray-500 text-2xl"
                >
                    &times;
                </button>
            </div>
            
           <div class="space-y-4 mb-4">
                    <lable>Invite User Name</lable>
                    <input  v-model="form.name"
                        type="text"
                        placeholder="Invite User Name"
                        class="w-full border rounded p-2"
                    />
                    <span
                        v-if="errors.name"
                         class="text-red-500 text-sm mt-1"
                    >
                     {{ errors.name[0] }}
                    </span>
                </div>

                <div class="space-y-4 mt-4">
                    <lable>Invite User Email</lable>
                    <input v-model="form.email"
                        type="text"
                        placeholder="Email"
                        class="w-full border rounded p-2"
                    />
                    <span
                          v-if="errors.email"
                         class="text-red-500 text-sm mt-1"
                    >
                     {{ errors.email[0] }}
                    </span>
                </div>

                  <div class="space-y-4 mt-4">
                    <lable>Invite User Role</lable>
                    <select v-model="form.role" class="w-full border rounded p-2">
                        <option value="Admin">Admin</option>
                        <option value="Member">Member</option>  
                    </select>    
                    <span
                          v-if="errors.role"
                         class="text-red-500 text-sm mt-1"
                    >
                     {{ errors.role[0] }}
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
                    {{ loading ? 'Inviting...' : 'Invite' }}
                </button>

            </div>

        </div>
    </div>
</template>